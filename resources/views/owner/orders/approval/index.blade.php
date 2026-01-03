{{-- File: resources/views/owner/orders/approval/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Approval Order')

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-check-circle me-2"></i>Approval Order Customer</h4>
        <span class="badge bg-warning px-3 py-2" style="font-size: 1rem;">
            {{ \App\Models\Order::pending()->count() }} Menunggu Approval
        </span>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time-five bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Menunggu Approval</p>
                            <h4 class="mb-0">{{ \App\Models\Order::pending()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-check-circle bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Disetujui</p>
                            <h4 class="mb-0">{{ \App\Models\Order::approved()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-x-circle bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Ditolak</p>
                            <h4 class="mb-0">{{ \App\Models\Order::where('status', 'rejected')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-money bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small">Total Value (Pending)</p>
                            <h5 class="mb-0">
                                Rp {{ number_format(\App\Models\Order::pending()->sum('total_amount'), 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label col-form-label-sm">Filter Status:</label>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Approval
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('owner.orders.approval.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bx bx-reset"></i> Reset
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
                        {{-- Customer Info --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bx bx-user text-primary me-2"></i>
                                <strong>{{ $order->customer_name }}</strong>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="bx bx-phone text-muted me-2"></i>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="bx bx-map text-muted me-2 mt-1"></i>
                                <small class="text-muted">{{ Str::limit($order->shipping_address, 80) }}</small>
                            </div>
                        </div>

                        {{-- Items Preview --}}
                        <h6 class="mb-2">Items ({{ $order->items->count() }})</h6>
                        @foreach ($order->items->take(3) as $item)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-package text-muted me-2"></i>
                                <span>{{ $item->product_name }} ({{ $item->quantity }}x)</span>
                            </div>
                        @endforeach
                        @if ($order->items->count() > 3)
                            <small class="text-muted">+ {{ $order->items->count() - 3 }} produk lainnya</small>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <h6 class="mb-2">Total Pembayaran</h6>
                        <h5 class="text-primary mb-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>

                        <a href="{{ route('owner.orders.approval.show', $order) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-show me-1"></i>
                            @if ($order->isPending())
                                Review & Approve
                            @else
                                Lihat Detail
                            @endif
                        </a>
                    </div>
                </div>

                {{-- Status Info based on Status --}}
                <div class="mt-3 pt-3 border-top">
                    @if ($order->isPending())
                        <div class="alert alert-warning py-2 mb-0">
                            <small>
                                <i class="bx bx-time me-1"></i>
                                Pesanan menunggu persetujuan Anda
                                @if ($order->created_at->diffInHours() > 24)
                                    <span class="badge bg-danger ms-2">Urgent! > 24 jam</span>
                                @endif
                            </small>
                        </div>
                    @elseif($order->isApproved())
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="bx bx-check-circle me-1"></i>
                                Pesanan sudah disetujui. Menunggu pembayaran dari customer.
                            </small>
                        </div>
                    @elseif($order->isRejected())
                        <div class="alert alert-danger py-2 mb-0">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                <strong>Alasan Penolakan:</strong> {{ $order->rejection_reason }}
                            </small>
                        </div>
                    @elseif($order->isPaid())
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bx bx-package me-1"></i>
                                Pembayaran sudah diterima. Pesanan sedang diproses.
                            </small>
                        </div>
                    @elseif($order->status == 'completed')
                        <div class="alert alert-secondary py-2 mb-0">
                            <small>
                                <i class="bx bx-check-double me-1"></i>
                                Pesanan sudah selesai.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-check-shield bx-lg text-muted mb-3"></i>
                <h5>Tidak Ada Order</h5>
                <p class="text-muted mb-0">
                    @if (request('status'))
                        Tidak ada order dengan status ini.
                    @else
                        Belum ada order yang memerlukan approval.
                    @endif
                </p>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="mt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
