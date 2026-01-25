{{-- File: resources/views/owner/orders/history/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Order - ' . $order->order_number)

@push('styles')
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 1.5rem;
            border-left: 2px solid #dee2e6;
            margin-left: 10px;
        }

        .timeline-item:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: #6c757d;
        }

        .timeline-item.active::before {
            background-color: #0d6efd;
        }

        .timeline-item.completed::before {
            background-color: #198754;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('owner.orders.history.index') }}">{{ __('orders.order_history') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bx bx-shopping-bag me-2"></i>Detail Order</h4>
            <p class="text-muted mb-0">{{ $order->order_number }} - {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="badge {{ $order->status_badge_class }} px-3 py-2" style="font-size: 0.9rem;">
            {{ $order->status_display }}
        </span>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8 mb-4">
            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-package me-2"></i>Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                        alt="{{ $item->product_name }}" class="product-img me-3">
                                                @else
                                                    <div
                                                        class="product-img me-3 bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bx bx-coffee text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if ($item->product)
                                                        <br><small class="text-muted">SKU:
                                                            {{ $item->product->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal</td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($order->tax_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            Pajak ({{ number_format($order->tax_rate, 1) }}%)
                                        </td>
                                        <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end">Ongkos Kirim</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end">
                                        <strong class="text-primary" style="font-size: 1.2rem;">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Shipping Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-map me-2"></i>Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>{{ $order->customer_name }}</strong></p>
                    <p class="mb-2">{{ $order->shipping_address }}</p>
                    <p class="mb-0">
                        <i class="bx bx-phone me-1"></i>{{ $order->customer_phone ?? '-' }}
                    </p>
                    @if ($order->tracking_number)
                        <hr>
                        <div class="alert alert-success mb-0">
                            <strong><i class="bx bx-barcode me-1"></i>Nomor Resi:</strong>
                            <span class="fs-5 ms-2">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Customer Notes --}}
            @if ($order->customer_notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-note me-2"></i>Catatan Customer</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->customer_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Customer Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nama</label>
                        <p class="mb-0 fw-bold">{{ $order->customer_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $order->customer_email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Telepon</label>
                        <p class="mb-0">{{ $order->customer_phone ?? '-' }}</p>
                    </div>
                    @if ($order->customer)
                        <a href="{{ route('owner.users.show', $order->customer) }}"
                            class="btn btn-outline-primary btn-sm w-100">
                            <i class="bx bx-user me-1"></i>Lihat Profil Customer
                        </a>
                    @endif
                </div>
            </div>

            {{-- Order Timeline --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-time-five me-2"></i>Timeline Order</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-item completed">
                        <strong>Order Dibuat</strong>
                        <p class="text-muted mb-0 small">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    @if ($order->approved_at)
                        <div class="timeline-item completed">
                            <strong>Disetujui</strong>
                            <p class="text-muted mb-0 small">
                                {{ $order->approved_at->format('d M Y, H:i') }}
                                @if ($order->approver)
                                    <br>oleh {{ $order->approver->name }}
                                @endif
                            </p>
                        </div>
                    @endif

                    @if ($order->payment && $order->payment->verified_at)
                        <div class="timeline-item completed">
                            <strong>Pembayaran Diverifikasi</strong>
                            <p class="text-muted mb-0 small">{{ $order->payment->verified_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif

                    @if ($order->shipped_at)
                        <div class="timeline-item completed">
                            <strong>Dikirim</strong>
                            <p class="text-muted mb-0 small">
                                {{ $order->shipped_at->format('d M Y, H:i') }}
                                @if ($order->tracking_number)
                                    <br>Resi: {{ $order->tracking_number }}
                                @endif
                            </p>
                        </div>
                    @endif

                    @if ($order->completed_at)
                        <div class="timeline-item completed">
                            <strong>Selesai</strong>
                            <p class="text-muted mb-0 small">{{ $order->completed_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif

                    @if ($order->isRejected())
                        <div class="timeline-item">
                            <strong class="text-danger">Ditolak</strong>
                            <p class="text-muted mb-0 small">{{ $order->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Invoice Section --}}
            @if (in_array($order->status, ['approved', 'paid', 'processing', 'shipped', 'completed']))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-file-blank me-2"></i>Invoice</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <a href="{{ route('invoices.preview', $order) }}" target="_blank"
                                class="btn btn-outline-primary flex-fill">
                                <i class="bx bx-show me-1"></i>Lihat
                            </a>
                            <a href="{{ route('invoices.download', $order) }}" class="btn btn-primary flex-fill">
                                <i class="bx bx-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('owner.orders.history.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
        </div>
    </div>
@endsection
