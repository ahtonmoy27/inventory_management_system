<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Expense;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function financialReport(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $salesQuery = Sale::whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate);

        $expensesQuery = Expense::whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate);

        $totalSales = (clone $salesQuery)->sum('total_amount');
        $totalDiscount = (clone $salesQuery)->sum('discount');
        $totalVat = (clone $salesQuery)->sum('vat_amount');
        $totalReceived = (clone $salesQuery)->sum('paid_amount');
        $totalDue = (clone $salesQuery)->sum('due_amount');
        $totalExpenses = (clone $expensesQuery)->sum('amount');

        $dailySales = Sale::selectRaw('DATE(sale_date) as date, SUM(total_amount) as total, SUM(paid_amount) as received, SUM(due_amount) as due, COUNT(*) as count')
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $dailyExpenses = Expense::selectRaw('DATE(expense_date) as date, SUM(amount) as total, COUNT(*) as count')
            ->whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $expensesByCategory = Expense::join('chart_of_accounts', 'expenses.account_id', '=', 'chart_of_accounts.id')
            ->selectRaw('chart_of_accounts.name as account_name, SUM(expenses.amount) as total')
            ->whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.name')
            ->get();

        $netProfit = $totalSales - $totalDiscount - $totalExpenses;

        return view('reports.financial', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalDiscount',
            'totalVat',
            'totalReceived',
            'totalDue',
            'totalExpenses',
            'netProfit',
            'dailySales',
            'dailyExpenses',
            'expensesByCategory'
        ));
    }

    public function accountBalances(): View
    {
        $accounts = ChartOfAccount::orderBy('type')->orderBy('code')->get();
        
        $groupedAccounts = $accounts->groupBy('type');

        return view('reports.account-balances', compact('groupedAccounts'));
    }
}
