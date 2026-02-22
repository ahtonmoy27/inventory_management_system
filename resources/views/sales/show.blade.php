@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Sale Details</h1>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice: {{ $sale->invoice_number }}</h5>
                @if($sale->status == 'paid')
                    <span class="badge bg-success fs-6">Paid</span>
                @elseif($sale->status == 'partial')
                    <span class="badge bg-warning fs-6">Partial Payment</span>
                @else
                    <span class="badge bg-danger fs-6">Payment Pending</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
                        <p><strong>Email:</strong> {{ $sale->customer->email ?? '-' }}</p>
                        <p><strong>Phone:</strong> {{ $sale->customer->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Sale Date:</strong> {{ $sale->sale_date->format('d M, Y') }}</p>
                        <p><strong>Created:</strong> {{ $sale->created_at->format('d M, Y H:i') }}</p>
                    </div>
                </div>

                <h6>Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->sku }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 2) }} TK</td>
                                <td class="text-end">{{ number_format($item->total_price, 2) }} TK</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">{{ number_format($sale->subtotal, 2) }} TK</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                <td class="text-end text-danger">-{{ number_format($sale->discount, 2) }} TK</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>VAT ({{ $sale->vat_percentage }}%):</strong></td>
                                <td class="text-end">{{ number_format($sale->vat_amount, 2) }} TK</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                <td class="text-end fw-bold">{{ number_format($sale->total_amount, 2) }} TK</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Paid Amount:</strong></td>
                                <td class="text-end text-success">{{ number_format($sale->paid_amount, 2) }} TK</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Due Amount:</strong></td>
                                <td class="text-end text-danger fw-bold">{{ number_format($sale->due_amount, 2) }} TK</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($sale->journalEntry)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Journal Entry: {{ $sale->journalEntry->entry_number }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th>Description</th>
                                <th class="text-end">Debit (TK)</th>
                                <th class="text-end">Credit (TK)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->journalEntry->lines as $line)
                            <tr>
                                <td>{{ $line->account->code }} - {{ $line->account->name }}</td>
                                <td>{{ $line->description }}</td>
                                <td class="text-end">{{ $line->debit > 0 ? number_format($line->debit, 2) : '-' }}</td>
                                <td class="text-end">{{ $line->credit > 0 ? number_format($line->credit, 2) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>{{ number_format($sale->journalEntry->total_debit, 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($sale->journalEntry->total_credit, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>{{ number_format($sale->subtotal, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <strong class="text-danger">-{{ number_format($sale->discount, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>VAT ({{ $sale->vat_percentage }}%):</span>
                    <strong>{{ number_format($sale->vat_amount, 2) }} TK</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fs-5">Total:</span>
                    <strong class="fs-5">{{ number_format($sale->total_amount, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Paid:</span>
                    <strong class="text-success">{{ number_format($sale->paid_amount, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Due:</span>
                    <strong class="text-danger">{{ number_format($sale->due_amount, 2) }} TK</strong>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Cost Analysis</h5>
            </div>
            <div class="card-body">
                @php
                    $cogs = 0;
                    foreach($sale->items as $item) {
                        $cogs += $item->quantity * $item->product->purchase_price;
                    }
                    $grossProfit = $sale->subtotal - $sale->discount - $cogs;
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span>Cost of Goods:</span>
                    <strong>{{ number_format($cogs, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Gross Profit:</span>
                    <strong class="{{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($grossProfit, 2) }} TK
                    </strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Profit Margin:</span>
                    <strong>
                        @if($sale->subtotal > 0)
                            {{ number_format(($grossProfit / $sale->subtotal) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
