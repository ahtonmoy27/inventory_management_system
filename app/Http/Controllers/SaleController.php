<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private AccountingService $accountingService)
    {
    }

    public function index(): View
    {
        $sales = Sale::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('current_stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $items = [];

            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                
                if ($product->current_stock < $productData['quantity']) {
                    return redirect()->back()->with('error', "Insufficient stock for {$product->name}");
                }

                $itemTotal = $product->sell_price * $productData['quantity'];
                $subtotal += $itemTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $product->sell_price,
                    'total_price' => $itemTotal,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $vatPercentage = $validated['vat_percentage'] ?? 0;
            $amountAfterDiscount = $subtotal - $discount;
            $vatAmount = ($amountAfterDiscount * $vatPercentage) / 100;
            $totalAmount = $amountAfterDiscount + $vatAmount;
            $paidAmount = $validated['paid_amount'] ?? 0;
            $dueAmount = $totalAmount - $paidAmount;

            $status = 'pending';
            if ($paidAmount >= $totalAmount) {
                $status = 'paid';
                $paidAmount = $totalAmount;
                $dueAmount = 0;
            } elseif ($paidAmount > 0) {
                $status = 'partial';
            }

            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);

            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                $item['product']->decrement('current_stock', $item['quantity']);
            }

            if ($dueAmount > 0) {
                $customer = Customer::find($validated['customer_id']);
                $customer->increment('total_due', $dueAmount);
            }

            $this->accountingService->createSaleJournalEntry($sale->load('items.product'));

            return redirect()->route('sales.show', $sale)->with('success', 'Sale created successfully.');
        });
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'items.product', 'journalEntry.lines.account']);
        return view('sales.show', compact('sale'));
    }
}
