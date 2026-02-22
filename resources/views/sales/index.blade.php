@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Sales</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Sale
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer->name }}</td>
                        <td>{{ $sale->sale_date->format('d M, Y') }}</td>
                        <td>{{ number_format($sale->total_amount, 2) }} TK</td>
                        <td>{{ number_format($sale->paid_amount, 2) }} TK</td>
                        <td>
                            @if($sale->due_amount > 0)
                                <span class="text-danger">{{ number_format($sale->due_amount, 2) }} TK</span>
                            @else
                                <span class="text-success">0.00 TK</span>
                            @endif
                        </td>
                        <td>
                            @if($sale->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($sale->status == 'partial')
                                <span class="badge bg-warning">Partial</span>
                            @else
                                <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No sales found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $sales->links() }}
    </div>
</div>
@endsection
