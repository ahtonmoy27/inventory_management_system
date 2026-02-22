@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Product Details</h1>
    <div>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $product->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>SKU:</strong> {{ $product->sku }}</p>
                        <p><strong>Purchase Price:</strong> {{ number_format($product->purchase_price, 2) }} TK</p>
                        <p><strong>Sell Price:</strong> {{ number_format($product->sell_price, 2) }} TK</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Opening Stock:</strong> {{ $product->opening_stock }}</p>
                        <p><strong>Current Stock:</strong> {{ $product->current_stock }}</p>
                        <p><strong>Stock Value:</strong> {{ number_format($product->stock_value, 2) }} TK</p>
                    </div>
                </div>
                @if($product->description)
                <hr>
                <p><strong>Description:</strong></p>
                <p>{{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Stock Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Profit Margin:</span>
                    <strong class="text-success">{{ number_format($product->sell_price - $product->purchase_price, 2) }} TK</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Margin %:</span>
                    <strong>{{ number_format((($product->sell_price - $product->purchase_price) / $product->purchase_price) * 100, 1) }}%</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Stock Value:</span>
                    <strong>{{ number_format($product->stock_value, 2) }} TK</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
