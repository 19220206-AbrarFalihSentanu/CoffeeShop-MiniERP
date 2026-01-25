{{-- File: resources/views/owner/purchase-orders/history/index.blade.php --}}

@extends('layouts.app')

@section('title', __('purchase_orders.po_history'))

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-archive me-2"></i>{{ __('purchase_orders.po_history') }}</h4>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1">Total PO</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="bx bx-purchase-tag bx-lg opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1">Diterima</h6>
                            <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                        </div>
                        <i class="bx bx-check-double bx-lg opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="bx bx-time bx-lg opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1">Disetujui</h6>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        </div>
                        <i class="bx bx-check bx-lg opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="No. PO / Supplier..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('owner.purchase-orders.history.index') }}" class="btn btn-secondary">
                        <i class="bx bx-reset me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- PO Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>PO #</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $po)
                            <tr>
                                <td>
                                    <strong>{{ $po->po_number }}</strong>
                                </td>
                                <td>
                                    {{ $po->supplier->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $po->supplier->phone ?? '' }}</small>
                                </td>
                                <td>
                                    {{ $po->items->count() }} item(s)
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $po->status_badge_class }}">
                                        {{ $po->status_display }}
                                    </span>
                                </td>
                                <td>
                                    {{ $po->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $po->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    {{ $po->creator->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('owner.purchase-orders.history.show', $po) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-package bx-lg text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Belum ada purchase order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($purchaseOrders->hasPages())
            <div class="card-footer">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>
@endsection
