{{-- File: resources/views/owner/orders/approval/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Review Order - ' . $order->order_number)

@push('styles')
    <style>
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stock-ok {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .stock-warning {
            background-color: #fff3cd;
            color: #664d03;
        }

        .stock-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        .approval-section {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
        }

        .product-check-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('owner.orders.approval.index') }}">Approval Order</a>
            </li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bx bx-check-shield me-2"></i>Review Order</h4>
            <p class="text-muted mb-0">{{ $order->order_number }} - Dibuat
                {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="badge {{ $order->status_badge_class }} px-3 py-2" style="font-size: 0.9rem;">
            {{ $order->status_display }}
        </span>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8 mb-4">
            {{-- Stock Availability Check --}}
            @php
                $stockIssues = [];
                $totalIssues = 0;
                foreach ($order->items as $item) {
                    $available = $item->product && $item->product->inventory ? $item->product->inventory->available : 0;
                    if ($available < $item->quantity) {
                        $stockIssues[] = [
                            'item' => $item,
                            'available' => $available,
                            'shortage' => $item->quantity - $available,
                        ];
                        $totalIssues++;
                    }
                }
            @endphp

            @if ($order->isPending() && count($stockIssues) > 0)
                <div class="alert alert-danger mb-4">
                    <h6 class="alert-heading">
                        <i class="bx bx-error-circle me-1"></i>Peringatan Stok Tidak Mencukupi!
                    </h6>
                    <p class="mb-2">
                        {{ count($stockIssues) }} produk memiliki stok yang tidak mencukupi untuk order ini:
                    </p>
                    <ul class="mb-0">
                        @foreach ($stockIssues as $issue)
                            <li>
                                <strong>{{ $issue['item']->product_name }}</strong>:
                                Dipesan {{ $issue['item']->quantity }}, Tersedia {{ $issue['available'] }}
                                <span class="text-danger fw-bold">(Kurang {{ $issue['shortage'] }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-package me-2"></i>Produk Dipesan</h5>
                    <span class="badge bg-label-primary">{{ $order->items->count() }} Item</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    @php
                                        $available =
                                            $item->product && $item->product->inventory
                                                ? $item->product->inventory->available
                                                : 0;
                                        $stockStatus =
                                            $available >= $item->quantity
                                                ? 'ok'
                                                : ($available > 0
                                                    ? 'warning'
                                                    : 'danger');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                        alt="{{ $item->product_name }}" class="product-check-img me-3">
                                                @else
                                                    <div
                                                        class="bg-light d-flex align-items-center justify-content-center product-check-img me-3">
                                                        <i class="bx bx-coffee text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $item->product_sku }} | {{ $item->product_weight }}g
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $item->quantity }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="stock-indicator stock-{{ $stockStatus }}">
                                                @if ($stockStatus == 'ok')
                                                    <i class="bx bx-check-circle me-1"></i>{{ $available }}
                                                @elseif($stockStatus == 'warning')
                                                    <i class="bx bx-error-circle me-1"></i>{{ $available }}
                                                @else
                                                    <i class="bx bx-x-circle me-1"></i>Habis
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($order->tax_amount > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">
                                            <strong>Pajak ({{ number_format($order->tax_rate, 1) }}%):</strong>
                                        </td>
                                        <td class="text-end fw-bold">Rp
                                            {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Ongkir:</strong></td>
                                        <td class="text-end fw-bold">Rp
                                            {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end">
                                        <h5 class="mb-0">TOTAL:</h5>
                                    </td>
                                    <td class="text-end">
                                        <h4 class="text-primary mb-0">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Nama Lengkap</small>
                            <strong>{{ $order->customer_name }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $order->customer_email }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">No. Telepon</small>
                            <strong>{{ $order->customer_phone }}</strong>
                        </div>
                        <div class="col-md-12 mb-0">
                            <small class="text-muted d-block">Alamat Pengiriman</small>
                            <strong>{{ $order->shipping_address }}</strong>
                        </div>
                    </div>

                    @if ($order->customer_notes)
                        <hr>
                        <div>
                            <small class="text-muted d-block mb-2"><i class="bx bx-note me-1"></i>Catatan dari
                                Customer:</small>
                            <div class="alert alert-info py-2 mb-0">
                                {{ $order->customer_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Approval Actions --}}
            @if ($order->canApprove())
                <div class="approval-section">
                    <h5 class="mb-4">
                        <i class="bx bx-check-shield me-2"></i>Keputusan Approval
                    </h5>

                    <div class="row g-3">
                        {{-- Approve Button --}}
                        <div class="col-md-6">
                            <form action="{{ route('owner.orders.approval.approve', $order) }}" method="POST"
                                onsubmit="return confirm('⚠️ KONFIRMASI APPROVE\n\n✅ Stok akan dikurangi otomatis\n💰 Transaksi akan tercatat di financial log\n📧 Customer akan menerima email notifikasi\n\nLanjutkan approve order ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 btn-lg"
                                    {{ count($stockIssues) > 0 ? 'disabled' : '' }}>
                                    <i class="bx bx-check-circle me-2"></i>
                                    Setujui Order
                                </button>
                                @if (count($stockIssues) > 0)
                                    <small class="text-danger d-block mt-2 text-center">
                                        <i class="bx bx-error-circle me-1"></i>
                                        Tidak dapat approve karena stok tidak mencukupi
                                    </small>
                                @endif
                            </form>
                        </div>

                        {{-- Reject Button --}}
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger w-100 btn-lg" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bx bx-x-circle me-2"></i>
                                Tolak Order
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4 mb-0">
                        <h6 class="alert-heading">
                            <i class="bx bx-info-circle me-1"></i>Perhatian!
                        </h6>
                        <ul class="mb-0">
                            <li>Setelah <strong>APPROVE</strong>, stok akan dikurangi secara otomatis dan tidak bisa
                                dibatalkan</li>
                            <li>Customer akan menerima email notifikasi untuk melakukan pembayaran</li>
                            <li>Jika <strong>REJECT</strong>, berikan alasan yang jelas agar customer dapat melakukan
                                perbaikan</li>
                        </ul>
                    </div>
                </div>
            @elseif($order->isApproved())
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="bx bx-check-circle me-1"></i>Order Telah Disetujui
                    </h6>
                    <p class="mb-1">
                        Disetujui oleh: <strong>{{ $order->approver->name ?? 'N/A' }}</strong>
                    </p>
                    <p class="mb-0">
                        Waktu Approval: <strong>{{ $order->approved_at->format('d M Y, H:i') }}</strong>
                    </p>
                </div>
            @elseif($order->isRejected())
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="bx bx-x-circle me-1"></i>Order Telah Ditolak
                    </h6>
                    <p class="mb-1">
                        Ditolak oleh: <strong>{{ $order->approver->name ?? 'N/A' }}</strong>
                    </p>
                    <p class="mb-1">
                        Waktu Penolakan: <strong>{{ $order->rejected_at->format('d M Y, H:i') }}</strong>
                    </p>
                    <p class="mb-0">
                        <strong>Alasan:</strong> {{ $order->rejection_reason }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Right Column - Summary --}}
        <div class="col-lg-4">
            {{-- Order Summary Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>Info Order</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Order Number</small>
                        <strong>{{ $order->order_number }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal Order</small>
                        <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge {{ $order->status_badge_class }}">{{ $order->status_display }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Item</small>
                        <strong>{{ $order->items->sum('quantity') }} unit</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Total Amount</small>
                        <h4 class="text-primary mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('owner.orders.approval.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bx bx-left-arrow-alt me-1"></i>Kembali ke List
                    </a>

                    @if ($order->canApprove())
                        <div class="alert alert-warning py-2 mb-0">
                            <small>
                                <i class="bx bx-time-five me-1"></i>
                                <strong>Menunggu keputusan Anda</strong>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('owner.orders.approval.reject', $order) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-x-circle me-2 text-danger"></i>Tolak Order
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <strong>Perhatian!</strong> Anda akan menolak order <strong>{{ $order->order_number }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="4"
                                placeholder="Contoh: Stok tidak mencukupi, Alamat pengiriman tidak jelas, dll." required>{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 10 karakter. Berikan alasan yang jelas agar customer
                                mengerti.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-x-circle me-1"></i>Ya, Tolak Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
