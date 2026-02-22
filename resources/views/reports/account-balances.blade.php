@extends('layouts.app')

@section('title', 'Account Balances')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chart of Accounts - Balances</h1>
</div>

@foreach($groupedAccounts as $type => $accounts)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ ucfirst($type) }} Accounts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Category</th>
                        <th class="text-end">Balance (TK)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>
                            {{ $account->name }}
                            @if($account->is_system)
                                <span class="badge bg-secondary ms-2">System</span>
                            @endif
                        </td>
                        <td>{{ $account->category }}</td>
                        <td class="text-end">
                            @if($account->balance >= 0)
                                <span class="text-success">{{ number_format($account->balance, 2) }}</span>
                            @else
                                <span class="text-danger">({{ number_format(abs($account->balance), 2) }})</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total {{ ucfirst($type) }}:</strong></td>
                        <td class="text-end">
                            @php $total = $accounts->sum('balance'); @endphp
                            @if($total >= 0)
                                <strong class="text-success">{{ number_format($total, 2) }} TK</strong>
                            @else
                                <strong class="text-danger">({{ number_format(abs($total), 2) }}) TK</strong>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endforeach

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Accounting Equation Summary</h5>
    </div>
    <div class="card-body">
        @php
            $assets = $groupedAccounts->get('asset', collect())->sum('balance');
            $liabilities = $groupedAccounts->get('liability', collect())->sum('balance');
            $equity = $groupedAccounts->get('equity', collect())->sum('balance');
            $revenue = $groupedAccounts->get('revenue', collect())->sum('balance');
            $expenses = $groupedAccounts->get('expense', collect())->sum('balance');
        @endphp
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <h6 class="text-muted">Total Assets</h6>
                    <h3 class="text-success">{{ number_format($assets, 2) }} TK</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <h6 class="text-muted">Total Liabilities</h6>
                    <h3 class="text-danger">{{ number_format($liabilities, 2) }} TK</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <h6 class="text-muted">Owner's Equity</h6>
                    <h3 class="text-primary">{{ number_format($equity, 2) }} TK</h3>
                </div>
            </div>
        </div>
        <hr>
        <div class="row text-center">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="text-success">{{ number_format($revenue, 2) }} TK</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <h6 class="text-muted">Total Expenses</h6>
                    <h3 class="text-danger">{{ number_format($expenses, 2) }} TK</h3>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <h5>Net Income: 
                @php $netIncome = $revenue - $expenses; @endphp
                <span class="{{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($netIncome, 2) }} TK
                </span>
            </h5>
        </div>
    </div>
</div>
@endsection
