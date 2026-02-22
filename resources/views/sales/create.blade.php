@extends('layouts.app')

@section('title', 'Create Sale')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Create Sale</h1>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Sale Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sale_date" class="form-label">Sale Date</label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Products</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="addProduct">
                        <i class="bi bi-plus-circle me-1"></i>Add Product
                    </button>
                </div>
                <div class="card-body">
                    <div id="productRows">
                        <div class="row product-row mb-3">
                            <div class="col-md-5">
                                <select class="form-select product-select" name="products[0][id]" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}" data-stock="{{ $product->current_stock }}">
                                            {{ $product->name }} (Stock: {{ $product->current_stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity-input" name="products[0][quantity]" placeholder="Qty" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control unit-price" readonly placeholder="Price">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control row-total" readonly placeholder="Total">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger remove-row" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="subtotal" readonly value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount (TK)</label>
                        <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="vat_percentage" class="form-label">VAT (%)</label>
                        <input type="number" step="0.01" class="form-control" id="vat_percentage" name="vat_percentage" value="0" min="0" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">VAT Amount</label>
                        <input type="text" class="form-control" id="vat_amount" readonly value="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control fw-bold fs-5" id="total_amount" readonly value="0.00">
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="paid_amount" class="form-label">Paid Amount (TK)</label>
                        <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Amount</label>
                        <input type="text" class="form-control text-danger" id="due_amount" readonly value="0.00">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let productIndex = 1;
const products = @json($products);

document.getElementById('addProduct').addEventListener('click', function() {
    const productRowsDiv = document.getElementById('productRows');
    const newRow = document.createElement('div');
    newRow.className = 'row product-row mb-3';
    newRow.innerHTML = `
        <div class="col-md-5">
            <select class="form-select product-select" name="products[${productIndex}][id]" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}" data-stock="{{ $product->current_stock }}">
                        {{ $product->name }} (Stock: {{ $product->current_stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control quantity-input" name="products[${productIndex}][quantity]" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control unit-price" readonly placeholder="Price">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control row-total" readonly placeholder="Total">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger remove-row">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    productRowsDiv.appendChild(newRow);
    productIndex++;
    attachEventListeners();
});

function attachEventListeners() {
    document.querySelectorAll('.product-select').forEach(select => {
        select.removeEventListener('change', handleProductChange);
        select.addEventListener('change', handleProductChange);
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.removeEventListener('input', handleQuantityChange);
        input.addEventListener('input', handleQuantityChange);
    });

    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.removeEventListener('click', handleRemoveRow);
        btn.addEventListener('click', handleRemoveRow);
    });
}

function handleProductChange(e) {
    const row = e.target.closest('.product-row');
    const selectedOption = e.target.options[e.target.selectedIndex];
    const price = selectedOption.dataset.price || 0;
    const stock = selectedOption.dataset.stock || 0;
    
    row.querySelector('.unit-price').value = parseFloat(price).toFixed(2);
    row.querySelector('.quantity-input').max = stock;
    
    calculateRowTotal(row);
    calculateTotals();
}

function handleQuantityChange(e) {
    const row = e.target.closest('.product-row');
    calculateRowTotal(row);
    calculateTotals();
}

function handleRemoveRow(e) {
    e.target.closest('.product-row').remove();
    calculateTotals();
}

function calculateRowTotal(row) {
    const price = parseFloat(row.querySelector('.unit-price').value) || 0;
    const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
    const total = price * quantity;
    row.querySelector('.row-total').value = total.toFixed(2);
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.row-total').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });

    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const vatPercentage = parseFloat(document.getElementById('vat_percentage').value) || 0;
    const amountAfterDiscount = subtotal - discount;
    const vatAmount = (amountAfterDiscount * vatPercentage) / 100;
    const totalAmount = amountAfterDiscount + vatAmount;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const dueAmount = totalAmount - paidAmount;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('vat_amount').value = vatAmount.toFixed(2);
    document.getElementById('total_amount').value = totalAmount.toFixed(2);
    document.getElementById('due_amount').value = dueAmount.toFixed(2);
}

document.getElementById('discount').addEventListener('input', calculateTotals);
document.getElementById('vat_percentage').addEventListener('input', calculateTotals);
document.getElementById('paid_amount').addEventListener('input', calculateTotals);

attachEventListeners();
</script>
@endpush
