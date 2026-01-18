{{-- File: resources/views/owner/reports/financial.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-2">
                    <span class="text-muted fw-light">Reports /</span> Financial
                </h4>
                <p class="text-muted mb-0">
                    Generate and export financial reports
                </p>
            </div>
            <div>
                <a href="{{ route('owner.financial.dashboard') }}" class="btn btn-label-primary me-2">
                    <i class='bx bx-line-chart me-1'></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Income</h6>
                                <h4 class="text-success mb-0">Rp {{ number_format($totals['income'], 0, ',', '.') }}</h4>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class='bx bx-trending-up'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Expense</h6>
                                <h4 class="text-danger mb-0">Rp {{ number_format($totals['expense'], 0, ',', '.') }}</h4>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class='bx bx-trending-down'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Net Profit/Loss</h6>
                                <h4 class="text-{{ $totals['net'] >= 0 ? 'success' : 'danger' }} mb-0">
                                    Rp {{ number_format($totals['net'], 0, ',', '.') }}
                                </h4>
                            </div>
                            <div class="avatar">
                                <span
                                    class="avatar-initial rounded bg-label-{{ $totals['net'] >= 0 ? 'success' : 'danger' }}">
                                    <i class='bx bx-wallet'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Export -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.reports.financial') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search me-1'></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Export Buttons -->
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted me-2">Export:</span>
                    <a href="{{ route('owner.reports.financial.export.excel', request()->query()) }}"
                        class="btn btn-sm btn-success me-2">
                        <i class='bx bx-file me-1'></i> Excel
                    </a>
                    <a href="{{ route('owner.reports.financial.export.pdf', request()->query()) }}"
                        class="btn btn-sm btn-danger">
                        <i class='bx bxs-file-pdf me-1'></i> PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Financial Transactions</h5>
            </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $log->transaction_date->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $log->type == 'income' ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">{{ $log->category_display }}</span>
                                </td>
                                <td>
                                    <div style="max-width: 250px;">
                                        {{ Str::limit($log->description, 50) }}
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class='bx bx-file bx-lg'></i>
                                        <p class="mt-2">No financial records found for this period</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                <x-pagination-with-info :paginator="$logs" />
            </div>
        </div>
    </div>
@endsection

