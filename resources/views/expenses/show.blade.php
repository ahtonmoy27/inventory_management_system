@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Expense Details</h1>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expense Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $expense->expense_date->format('d M, Y') }}</p>
                        <p><strong>Account:</strong> {{ $expense->account->code }} - {{ $expense->account->name }}</p>
                        <p><strong>Reference:</strong> {{ $expense->reference ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Amount:</strong> <span class="fs-4 text-danger">{{ number_format($expense->amount, 2) }} TK</span></p>
                        <p><strong>Created:</strong> {{ $expense->created_at->format('d M, Y H:i') }}</p>
                    </div>
                </div>
                <hr>
                <p><strong>Description:</strong></p>
                <p>{{ $expense->description }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
