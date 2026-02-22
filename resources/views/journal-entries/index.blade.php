@extends('layouts.app')

@section('title', 'Journal Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Journal Entries</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label for="reference_type" class="form-label">Type</label>
                <select class="form-select" id="reference_type" name="reference_type">
                    <option value="">All Types</option>
                    <option value="sale" {{ request('reference_type') == 'sale' ? 'selected' : '' }}>Sale</option>
                    <option value="expense" {{ request('reference_type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    <option value="product" {{ request('reference_type') == 'product' ? 'selected' : '' }}>Product (Opening Stock)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Entry #</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntries as $entry)
                    <tr>
                        <td>{{ $entry->entry_number }}</td>
                        <td>{{ $entry->entry_date->format('d M, Y') }}</td>
                        <td>
                            @if($entry->reference_type == 'sale')
                                <span class="badge bg-success">Sale</span>
                            @elseif($entry->reference_type == 'expense')
                                <span class="badge bg-danger">Expense</span>
                            @elseif($entry->reference_type == 'product')
                                <span class="badge bg-info">Opening Stock</span>
                            @else
                                <span class="badge bg-secondary">Other</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($entry->description, 40) }}</td>
                        <td class="text-end">{{ number_format($entry->total_debit, 2) }} TK</td>
                        <td class="text-end">{{ number_format($entry->total_credit, 2) }} TK</td>
                        <td>
                            <a href="{{ route('journal-entries.show', $entry) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No journal entries found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $journalEntries->withQueryString()->links() }}
    </div>
</div>
@endsection
