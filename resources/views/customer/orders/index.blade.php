{{-- File: resources/views/customer/orders/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-shopping-bag me-2"></i>Pesanan Saya</h4>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-shopping-bag me-1"></i>Belanja Lagi
        </a>
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
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-secondary">
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

                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-show me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>

                {{-- Action Buttons based on Status --}}
                <div class="mt-3 pt-3 border-top">
                    @if ($order->isPending())
                        <div class="alert alert-warning py-2 mb-2">
                            <small>
                                <i class="bx bx-time me-1"></i>
                                Pesanan menunggu persetujuan dari Owner
                            </small>
                        </div>
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-x-circle me-1"></i>Batalkan Pesanan
                            </button>
                        </form>
                    @elseif($order->isApproved())
                        <div class="alert alert-success py-2 mb-2">
                            <small>
                                <i class="bx bx-check-circle me-1"></i>
                                Pesanan disetujui! Silakan upload bukti pembayaran
                            </small>
                        </div>
                    @elseif($order->isRejected())
                        <div class="alert alert-danger py-2 mb-0">
                            <small>
                                <i class="bx bx-x-circle me-1"></i>
                                <strong>Alasan:</strong> {{ $order->rejection_reason }}
                            </small>
                        </div>
                    @elseif($order->isPaid())
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bx bx-package me-1"></i>
                                Pembayaran terverifikasi. Pesanan sedang diproses.
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
                <h5>Belum Ada Pesanan</h5>
                <p class="text-muted">Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    <i class="bx bx-shopping-bag me-1"></i>Mulai Belanja
                </a>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
