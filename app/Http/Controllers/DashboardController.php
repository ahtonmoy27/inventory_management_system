<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSales = Sale::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $lowStockProducts = Product::where('current_stock', '<', 10)->get();
        $recentSales = Sale::with('customer')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'totalSales',
            'totalExpenses',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
