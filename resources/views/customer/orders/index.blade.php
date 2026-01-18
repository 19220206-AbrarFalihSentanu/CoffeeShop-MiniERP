{{-- File: resources/views/customer/orders/index.blade.php --}}

@extends('layouts.app')

@section('title', __('orders.my_orders'))

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-shopping-bag me-2"></i>{{ __('orders.my_orders') }}</h4>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-shopping-bag me-1"></i>{{ __('orders.shop_again') }}
        </a>
    </div>

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label col-form-label-sm">{{ __('orders.filter_status') }}:</label>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('orders.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('orders.status_pending') }}
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            {{ __('orders.status_approved') }}
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            {{ __('orders.status_rejected') }}</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                            {{ __('orders.status_paid') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                            {{ __('orders.status_processing') }}
                        </option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                            {{ __('orders.status_shipped') }}
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            {{ __('orders.status_completed') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bx bx-reset"></i> {{ __('general.reset') }}
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
                    <div class="col-md-8">
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
                    </div>

                    <div class="col-md-4 text-end">
                        <h6 class="mb-2">{{ __('payments.total_payment') }}</h6>
                        <h5 class="text-primary mb-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>

                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-show me-1"></i>{{ __('general.view_detail') }}
                        </a>
                    </div>
                </div>

                {{-- Action Buttons based on Status --}}
                <div class="mt-3 pt-3 border-top">
                    @if ($order->isPending())
                        <div class="alert alert-warning py-2 mb-2">
                            <small>
                                <i class="bx bx-time me-1"></i>
                                {{ __('orders.waiting_approval') }}
                            </small>
                        </div>
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline"
                            data-confirm="{{ __('orders.confirm_cancel') }}" data-confirm-title="Batalkan Pesanan?"
                            data-confirm-icon="warning" data-confirm-button="Ya, Batalkan!" data-confirm-danger="true">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-x-circle me-1"></i>{{ __('orders.cancel_order') }}
                            </button>
                        </form>
                    @elseif($order->isApproved() && !$order->hasPayment())
                        <div class="alert alert-success py-2 mb-2">
                            <small>
                                <i class="bx bx-check-circle me-1"></i>
                                {{ __('orders.order_approved_upload') }}
                            </small>
                        </div>
                    @elseif($order->isApproved() && $order->payment?->isPending())
                        <div class="alert alert-info py-2 mb-2">
                            <small>
                                <i class="bx bx-time-five me-1"></i>
                                {{ __('orders.payment_verifying') }}
                            </small>
                        </div>
                    @elseif($order->isApproved() && $order->payment?->isRejected())
                        <div class="alert alert-danger py-2 mb-2">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                <strong>{{ __('orders.payment_rejected') }}!</strong> {{ __('orders.please_reupload') }}
                            </small>
                        </div>
                    @elseif($order->isRejected())
                        <div class="alert alert-danger py-2 mb-0">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                <strong>{{ __('orders.rejection_reason') }}:</strong> {{ $order->rejection_reason }}
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
                    @elseif($order->isShipped())
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bx bx-truck me-1"></i>
                                {{ __('orders.in_delivery') }}
                                @if ($order->tracking_number)
                                    <br><strong>{{ __('orders.tracking_number') }}:</strong> {{ $order->tracking_number }}
                                @endif
                            </small>
                        </div>
                    @elseif($order->isCompleted())
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="bx bx-check-double me-1"></i>
                                {{ __('orders.order_completed_thanks') }}
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
                <h5>{{ __('orders.no_orders_yet') }}</h5>
                <p class="text-muted">{{ __('orders.no_orders_message') }}</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    <i class="bx bx-shopping-bag me-1"></i>{{ __('orders.start_shopping') }}
                </a>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    <x-pagination-with-info :paginator="$orders" />
@endsection

