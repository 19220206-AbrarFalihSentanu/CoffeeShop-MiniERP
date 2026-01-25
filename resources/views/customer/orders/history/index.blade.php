{{-- File: resources/views/customer/orders/history/index.blade.php --}}

@extends('layouts.app')

@section('title', __('orders.order_history'))

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-history me-2"></i>{{ __('orders.order_history') }}</h4>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-shopping-bag me-1"></i>{{ __('orders.shop_again') }}
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1 small">Total Order</h6>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        </div>
                        <i class="bx bx-shopping-bag bx-md opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1 small">Selesai</h6>
                            <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                        </div>
                        <i class="bx bx-check-double bx-md opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1 small">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                        </div>
                        <i class="bx bx-time bx-md opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white mb-1 small">Diproses</h6>
                            <h4 class="mb-0">{{ $stats['processing'] }}</h4>
                        </div>
                        <i class="bx bx-loader-circle bx-md opacity-75"></i>
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
                    <input type="text" name="search" class="form-control" placeholder="No. Order..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('orders.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('orders.status_pending') }}</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            {{ __('orders.status_approved') }}</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                            {{ __('orders.status_paid') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                            {{ __('orders.status_processing') }}</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                            {{ __('orders.status_shipped') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            {{ __('orders.status_completed') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            {{ __('orders.status_rejected') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            {{ __('orders.status_cancelled') }}</option>
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
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i>
                    </button>
                    <a href="{{ route('customer.orders.history.index') }}" class="btn btn-secondary">
                        <i class="bx bx-reset"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders List --}}
    @forelse($orders as $order)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $order->order_number }}</strong>
                    <span class="text-muted ms-2">|</span>
                    <small class="text-muted ms-2">{{ $order->created_at->format('d M Y, H:i') }}</small>
                </div>
                <span class="badge {{ $order->status_badge_class }}">
                    {{ $order->status_display }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="mb-2">Items ({{ $order->items->count() }})</h6>
                        @foreach ($order->items->take(3) as $item)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-package text-muted me-2"></i>
                                <span>{{ $item->product_name }} ({{ $item->quantity }}x)</span>
                            </div>
                        @endforeach
                        @if ($order->items->count() > 3)
                            <small class="text-muted">+ {{ $order->items->count() - 3 }}
                                {{ __('orders.other_products') }}</small>
                        @endif

                        {{-- Tracking Number --}}
                        @if ($order->tracking_number)
                            <div class="mt-3">
                                <span class="badge bg-label-success">
                                    <i class="bx bx-barcode me-1"></i>Resi: {{ $order->tracking_number }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-5 text-end">
                        <h6 class="mb-2">{{ __('payments.total_payment') }}</h6>
                        <h5 class="text-primary mb-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>

                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-show me-1"></i>{{ __('general.view_detail') }}
                        </a>

                        {{-- Download Invoice Button --}}
                        @if (in_array($order->status, ['approved', 'paid', 'processing', 'shipped', 'completed']))
                            <a href="{{ route('invoices.download', $order) }}" class="btn btn-outline-success btn-sm">
                                <i class="bx bx-download me-1"></i>Invoice
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Status Info --}}
                <div class="mt-3 pt-3 border-top">
                    @if ($order->isCompleted())
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="bx bx-check-double me-1"></i>
                                {{ __('orders.order_completed') }} - {{ $order->completed_at?->format('d M Y, H:i') }}
                            </small>
                        </div>
                    @elseif($order->isShipped())
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bx bx-truck me-1"></i>
                                {{ __('orders.in_delivery') }}
                                @if ($order->tracking_number)
                                    - <strong>{{ __('orders.tracking_number') }}:</strong> {{ $order->tracking_number }}
                                @endif
                            </small>
                        </div>
                    @elseif($order->isRejected())
                        <div class="alert alert-danger py-2 mb-0">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                <strong>{{ __('orders.rejection_reason') }}:</strong> {{ $order->rejection_reason }}
                            </small>
                        </div>
                    @elseif($order->status === 'cancelled')
                        <div class="alert alert-secondary py-2 mb-0">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                {{ __('orders.order_cancelled') }}
                            </small>
                        </div>
                    @elseif($order->isPending())
                        <div class="alert alert-warning py-2 mb-0">
                            <small>
                                <i class="bx bx-time me-1"></i>
                                {{ __('orders.waiting_approval') }}
                            </small>
                        </div>
                    @elseif($order->isApproved() && !$order->hasPayment())
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="bx bx-check-circle me-1"></i>
                                {{ __('orders.order_approved_upload') }}
                            </small>
                        </div>
                    @elseif($order->isPaid())
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bx bx-credit-card me-1"></i>
                                {{ __('orders.payment_verified_processing') }}
                            </small>
                        </div>
                    @elseif($order->isProcessing())
                        <div class="alert alert-primary py-2 mb-0">
                            <small>
                                <i class="bx bx-box me-1"></i>
                                {{ __('orders.being_packed') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-shopping-bag bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('orders.no_orders') }}</h5>
                <p class="text-muted mb-3">{{ __('orders.start_shopping') }}</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    <i class="bx bx-store me-1"></i>{{ __('orders.browse_products') }}
                </a>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
