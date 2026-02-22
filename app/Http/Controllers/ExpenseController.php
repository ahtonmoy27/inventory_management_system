<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Expense;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(private AccountingService $accountingService)
    {
    }

    public function index(): View
    {
        $expenses = Expense::with('account')->latest()->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        $accounts = ChartOfAccount::where('type', 'expense')->orderBy('name')->get();
        return view('expenses.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'reference' => 'nullable|string|max:100',
        ]);

        $expense = Expense::create($validated);
        $this->accountingService->createExpenseJournalEntry($expense);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense): View
    {
        $expense->load('account');
        return view('expenses.show', compact('expense'));
    }
}
