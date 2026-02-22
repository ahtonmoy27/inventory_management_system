@extends('layouts.app')

@section('title', 'Journal Entry Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Journal Entry Details</h1>
    <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $journalEntry->entry_number }}</h5>
                @if($journalEntry->reference_type == 'sale')
                    <span class="badge bg-success fs-6">Sale Entry</span>
                @elseif($journalEntry->reference_type == 'expense')
                    <span class="badge bg-danger fs-6">Expense Entry</span>
                @elseif($journalEntry->reference_type == 'product')
                    <span class="badge bg-info fs-6">Opening Stock Entry</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Entry Date:</strong> {{ $journalEntry->entry_date->format('d M, Y') }}</p>
                        <p><strong>Description:</strong> {{ $journalEntry->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Reference Type:</strong> {{ ucfirst($journalEntry->reference_type ?? 'Manual') }}</p>
                        <p><strong>Created:</strong> {{ $journalEntry->created_at->format('d M, Y H:i') }}</p>
                    </div>
                </div>

                <h6 class="mb-3">Entry Lines</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account Code</th>
                                <th>Account Name</th>
                                <th>Description</th>
                                <th class="text-end">Debit (TK)</th>
                                <th class="text-end">Credit (TK)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journalEntry->lines as $line)
                            <tr>
                                <td>{{ $line->account->code }}</td>
                                <td>{{ $line->account->name }}</td>
                                <td>{{ $line->description ?? '-' }}</td>
                                <td class="text-end">{{ $line->debit > 0 ? number_format($line->debit, 2) : '-' }}</td>
                                <td class="text-end">{{ $line->credit > 0 ? number_format($line->credit, 2) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>{{ number_format($journalEntry->total_debit, 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($journalEntry->total_credit, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($journalEntry->total_debit == $journalEntry->total_credit)
                <div class="alert alert-success mt-3">
                    <i class="bi bi-check-circle me-2"></i>This journal entry is balanced.
                </div>
                @else
                <div class="alert alert-danger mt-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>Warning: This journal entry is not balanced!
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
