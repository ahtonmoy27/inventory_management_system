<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    public function createSaleJournalEntry(Sale $sale): JournalEntry
    {
        return DB::transaction(function () use ($sale) {
            $costOfGoodsSold = $this->calculateCOGS($sale);
            
            $journalEntry = JournalEntry::create([
                'entry_number' => JournalEntry::generateEntryNumber(),
                'entry_date' => $sale->sale_date,
                'reference_type' => 'sale',
                'reference_id' => $sale->id,
                'description' => "Sale Invoice: {$sale->invoice_number}",
                'total_debit' => $sale->subtotal + $costOfGoodsSold,
                'total_credit' => $sale->subtotal + $costOfGoodsSold,
            ]);

            $lines = [];

            if ($sale->paid_amount > 0) {
                $lines[] = [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('1001')->id,
                    'debit' => $sale->paid_amount,
                    'credit' => 0,
                    'description' => 'Cash received from customer',
                ];
            }

            if ($sale->due_amount > 0) {
                $lines[] = [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('1002')->id,
                    'debit' => $sale->due_amount,
                    'credit' => 0,
                    'description' => 'Amount due from customer',
                ];
            }

            if ($sale->discount > 0) {
                $lines[] = [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('4002')->id,
                    'debit' => $sale->discount,
                    'credit' => 0,
                    'description' => 'Sales discount given',
                ];
            }

            $lines[] = [
                'journal_entry_id' => $journalEntry->id,
                'account_id' => ChartOfAccount::getByCode('4001')->id,
                'debit' => 0,
                'credit' => $sale->subtotal,
                'description' => 'Sales revenue',
            ];

            if ($sale->vat_amount > 0) {
                $lines[] = [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('2002')->id,
                    'debit' => 0,
                    'credit' => $sale->vat_amount,
                    'description' => 'VAT collected on sale',
                ];
            }

            $lines[] = [
                'journal_entry_id' => $journalEntry->id,
                'account_id' => ChartOfAccount::getByCode('5001')->id,
                'debit' => $costOfGoodsSold,
                'credit' => 0,
                'description' => 'Cost of goods sold',
            ];

            $lines[] = [
                'journal_entry_id' => $journalEntry->id,
                'account_id' => ChartOfAccount::getByCode('1003')->id,
                'debit' => 0,
                'credit' => $costOfGoodsSold,
                'description' => 'Inventory reduced',
            ];

            foreach ($lines as $line) {
                JournalEntryLine::create($line);
            }

            $this->updateAccountBalances($lines);

            return $journalEntry;
        });
    }

    public function createProductOpeningStockEntry(Product $product): JournalEntry
    {
        return DB::transaction(function () use ($product) {
            $stockValue = $product->opening_stock * $product->purchase_price;

            $journalEntry = JournalEntry::create([
                'entry_number' => JournalEntry::generateEntryNumber(),
                'entry_date' => now(),
                'reference_type' => 'product',
                'reference_id' => $product->id,
                'description' => "Opening stock for product: {$product->name}",
                'total_debit' => $stockValue,
                'total_credit' => $stockValue,
            ]);

            $lines = [
                [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('1003')->id,
                    'debit' => $stockValue,
                    'credit' => 0,
                    'description' => 'Inventory added',
                ],
                [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('3001')->id,
                    'debit' => 0,
                    'credit' => $stockValue,
                    'description' => 'Owner equity - opening stock',
                ],
            ];

            foreach ($lines as $line) {
                JournalEntryLine::create($line);
            }

            $this->updateAccountBalances($lines);

            return $journalEntry;
        });
    }

    public function createExpenseJournalEntry(Expense $expense): JournalEntry
    {
        return DB::transaction(function () use ($expense) {
            $journalEntry = JournalEntry::create([
                'entry_number' => JournalEntry::generateEntryNumber(),
                'entry_date' => $expense->expense_date,
                'reference_type' => 'expense',
                'reference_id' => $expense->id,
                'description' => "Expense: {$expense->description}",
                'total_debit' => $expense->amount,
                'total_credit' => $expense->amount,
            ]);

            $lines = [
                [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $expense->account_id,
                    'debit' => $expense->amount,
                    'credit' => 0,
                    'description' => $expense->description,
                ],
                [
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => ChartOfAccount::getByCode('1001')->id,
                    'debit' => 0,
                    'credit' => $expense->amount,
                    'description' => 'Cash paid for expense',
                ],
            ];

            foreach ($lines as $line) {
                JournalEntryLine::create($line);
            }

            $this->updateAccountBalances($lines);

            return $journalEntry;
        });
    }

    private function calculateCOGS(Sale $sale): float
    {
        $cogs = 0;
        foreach ($sale->items as $item) {
            $cogs += $item->quantity * $item->product->purchase_price;
        }
        return $cogs;
    }

    private function updateAccountBalances(array $lines): void
    {
        foreach ($lines as $line) {
            $account = ChartOfAccount::find($line['account_id']);
            if (!$account) continue;

            $netChange = $line['debit'] - $line['credit'];

            if (in_array($account->type, ['asset', 'expense'])) {
                $account->balance += $netChange;
            } else {
                $account->balance -= $netChange;
            }

            $account->save();
        }
    }
}
