@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
    <div>
        <span class="badge bg-primary fs-6">{{ now()->format('l, F j, Y') }}</span>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Products</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-cart-check text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Sales</h6>
                        <h3 class="mb-0">{{ number_format($totalSales, 2) }} TK</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Customers</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-cash-stack text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Expenses</h6>
                        <h3 class="mb-0">{{ number_format($totalExpenses, 2) }} TK</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Recent Sales</h5>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                                <td>{{ $sale->customer->name }}</td>
                                <td>{{ $sale->sale_date->format('d M, Y') }}</td>
                                <td>{{ number_format($sale->total_amount, 2) }} TK</td>
                                <td>
                                    @if($sale->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($sale->status == 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No sales yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Low Stock Alert</h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($lowStockProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $product->name }}
                        <span class="badge bg-danger rounded-pill">{{ $product->current_stock }} left</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="mt-2">All products well stocked</p>
                </div>
                @endif
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Product
                    </a>
                    <a href="{{ route('sales.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-cart-plus me-2"></i>New Sale
                    </a>
                    <a href="{{ route('reports.financial') }}" class="btn btn-outline-info">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
