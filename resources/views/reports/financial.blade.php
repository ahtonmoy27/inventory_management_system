@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Financial Report</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Sales</h6>
                        <h3 class="mb-0">{{ number_format($totalSales, 2) }} TK</h3>
                    </div>
                    <i class="bi bi-cart-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Expenses</h6>
                        <h3 class="mb-0">{{ number_format($totalExpenses, 2) }} TK</h3>
                    </div>
                    <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Due</h6>
                        <h3 class="mb-0">{{ number_format($totalDue, 2) }} TK</h3>
                    </div>
                    <i class="bi bi-clock-history fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card {{ $netProfit >= 0 ? 'bg-primary' : 'bg-dark' }} text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Net Profit</h6>
                        <h3 class="mb-0">{{ number_format($netProfit, 2) }} TK</h3>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Sales Amount:</span>
                    <strong>{{ number_format($totalSales, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Discount Given:</span>
                    <strong class="text-danger">-{{ number_format($totalDiscount, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total VAT Collected:</span>
                    <strong>{{ number_format($totalVat, 2) }} TK</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Received:</span>
                    <strong class="text-success">{{ number_format($totalReceived, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Due:</span>
                    <strong class="text-danger">{{ number_format($totalDue, 2) }} TK</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expenses by Category</h5>
            </div>
            <div class="card-body">
                @if($expensesByCategory->count() > 0)
                    @foreach($expensesByCategory as $expense)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $expense->account_name }}:</span>
                        <strong>{{ number_format($expense->total, 2) }} TK</strong>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span><strong>Total Expenses:</strong></span>
                        <strong class="text-danger">{{ number_format($totalExpenses, 2) }} TK</strong>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">No expenses in this period</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daily Sales</h5>
            </div>
            <div class="card-body">
                @if($dailySales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Orders</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Received</th>
                                <th class="text-end">Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailySales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
                                <td class="text-center">{{ $sale->count }}</td>
                                <td class="text-end">{{ number_format($sale->total, 2) }} TK</td>
                                <td class="text-end text-success">{{ number_format($sale->received, 2) }} TK</td>
                                <td class="text-end text-danger">{{ number_format($sale->due, 2) }} TK</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No sales in this period</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daily Expenses</h5>
            </div>
            <div class="card-body">
                @if($dailyExpenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Count</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyExpenses as $expense)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                                <td class="text-center">{{ $expense->count }}</td>
                                <td class="text-end text-danger">{{ number_format($expense->total, 2) }} TK</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No expenses in this period</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
