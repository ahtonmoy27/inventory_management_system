@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Expenses</h1>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Expense
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('d M, Y') }}</td>
                        <td>{{ $expense->account->name }}</td>
                        <td>{{ Str::limit($expense->description, 50) }}</td>
                        <td>{{ $expense->reference ?? '-' }}</td>
                        <td class="text-end">{{ number_format($expense->amount, 2) }} TK</td>
                        <td>
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No expenses found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $expenses->links() }}
    </div>
</div>
@endsection
