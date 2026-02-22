@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Customer Details</h1>
    <div>
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $customer->name }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
                <p><strong>Address:</strong> {{ $customer->address ?? '-' }}</p>
                <hr>
                <p><strong>Total Due:</strong> 
                    @if($customer->total_due > 0)
                        <span class="text-danger">{{ number_format($customer->total_due, 2) }} TK</span>
                    @else
                        <span class="text-success">0.00 TK</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales History</h5>
            </div>
            <div class="card-body">
                @if($customer->sales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->sales as $sale)
                            <tr>
                                <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                                <td>{{ $sale->sale_date->format('d M, Y') }}</td>
                                <td>{{ number_format($sale->total_amount, 2) }} TK</td>
                                <td>{{ number_format($sale->paid_amount, 2) }} TK</td>
                                <td>{{ number_format($sale->due_amount, 2) }} TK</td>
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
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No sales history</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
