{{-- File: resources/views/owner/financial/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Logs')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-2">
                    <span class="text-muted fw-light">Financial /</span> All Logs
                </h4>
            </div>
            <div>
                <a href="{{ route('owner.financial.dashboard') }}" class="btn btn-label-primary me-2">
                    <i class='bx bx-line-chart me-1'></i> Dashboard
                </a>
                <a href="{{ route('owner.financial.expense.create') }}" class="btn btn-primary">
                    <i class='bx bx-plus me-1'></i> Add Expense
                </a>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Total Income</h6>
                        <h4 class="text-success mb-0">Rp {{ number_format($totals['income'], 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Total Expense</h6>
                        <h4 class="text-danger mb-0">Rp {{ number_format($totals['expense'], 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Net Profit/Loss</h6>
                        <h4 class="text-{{ $totals['net'] >= 0 ? 'success' : 'danger' }} mb-0">
                            Rp {{ number_format($totals['net'], 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Total Transactions</h6>
                        <h4 class="mb-0">{{ $logs->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.financial.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search description..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th>Created By</th>
                            <th>Reference</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $log->transaction_date->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $log->type_badge_class }}">
                                        {{ $log->type_display }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">{{ $log->category_display }}</span>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($log->description, 60) }}
                                    </div>
                                </td>
                                <td class="text-end">
                                    <strong class="text-{{ $log->type == 'income' ? 'success' : 'danger' }}">
                                        {{ $log->type == 'income' ? '+' : '-' }}
                                        Rp {{ number_format($log->amount, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <small>{{ $log->creator->name ?? '-' }}</small>
                                </td>
                                <td>
                                    @if ($log->reference_type)
                                        <small class="text-muted">
                                            {{ class_basename($log->reference_type) }} #{{ $log->reference_id }}
                                        </small>
                                    @else
                                        <span class="badge bg-label-warning">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$log->reference_type)
                                        <form action="{{ route('owner.financial.destroy', $log) }}" method="POST"
                                            onsubmit="return confirm('Delete this log?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @else
                                        <small class="text-muted">Auto</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class='bx bx-file bx-lg'></i>
                                        <p class="mt-2">No financial logs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($logs->hasPages())
                <div class="card-footer">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
