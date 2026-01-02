{{-- File: resources/views/customer/orders/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@push('styles')
    <style>
        .order-timeline {
            position: relative;
            padding-left: 30px;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 5px;
            bottom: 5px;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #adb5bd;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-item.active::before {
            background-color: #696cff;
            box-shadow: 0 0 0 2px #696cff33;
        }

        .timeline-item.completed::before {
            background-color: #71dd37;
            box-shadow: 0 0 0 2px #71dd3733;
        }

        .timeline-item.error::before {
            background-color: #ff3e1d;
            box-shadow: 0 0 0 2px #ff3e1d33;
        }

        .product-image-sm {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .payment-proof-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .payment-proof-preview:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bx bx-receipt me-2"></i>{{ $order->order_number }}</h4>
            <p class="text-muted mb-0">
                <i class="bx bx-calendar me-1"></i>{{ $order->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <span class="badge {{ $order->status_badge_class }} fs-6 px-3 py-2">
            {{ $order->status_display }}
        </span>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Order Status Alert --}}
            @if ($order->isPending())
                <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-time-five bx-md me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Menunggu Persetujuan</h6>
                        <p class="mb-0">Pesanan Anda sedang menunggu persetujuan dari Owner. Anda dapat membatalkan
                            pesanan selama masih dalam status ini.</p>
                    </div>
                </div>
            @elseif($order->isApproved())
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-check-circle bx-md me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Pesanan Disetujui!</h6>
                        <p class="mb-0">Silakan upload bukti pembayaran untuk melanjutkan proses pesanan Anda.</p>
                    </div>
                </div>
            @elseif($order->isRejected())
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-x-circle bx-md me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Pesanan Ditolak</h6>
                        <p class="mb-0"><strong>Alasan:</strong> {{ $order->rejection_reason }}</p>
                    </div>
                </div>
            @elseif($order->isPaid())
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-package bx-md me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Pembayaran Terverifikasi</h6>
                        <p class="mb-0">Pesanan Anda sedang diproses dan akan segera dikirim.</p>
                    </div>
                </div>
            @elseif($order->status === 'completed')
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bx bx-check-double bx-md me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Pesanan Selesai</h6>
                        <p class="mb-0">Terima kasih telah berbelanja di toko kami!</p>
                    </div>
                </div>
            @endif

            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-package me-2"></i>Produk yang Dipesan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
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
                                                        alt="{{ $item->product_name }}" class="product-image-sm me-3">
                                                @else
                                                    <div
                                                        class="bg-light d-flex align-items-center justify-content-center product-image-sm me-3">
                                                        <i class="bx bx-coffee text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if ($item->product)
                                                        <small
                                                            class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->quantity }}
                                        </td>
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
                                            Pajak PPN ({{ number_format($order->tax_rate, 1) }}%)
                                        </td>
                                        <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Ongkos Kirim</td>
                                        <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end">
                                        <strong class="text-primary fs-5">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Upload Payment Proof (if approved) --}}
            @if ($order->canUploadPayment())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-upload me-2"></i>Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if ($order->payment_proof)
                            <div class="alert alert-info mb-3">
                                <i class="bx bx-info-circle me-1"></i>
                                Anda sudah mengupload bukti pembayaran. Upload ulang jika diperlukan.
                            </div>
                            <div class="mb-3 text-center">
                                <p class="mb-2">Bukti Pembayaran Saat Ini:</p>
                                <a href="{{ Storage::url($order->payment_proof) }}" target="_blank">
                                    <img src="{{ Storage::url($order->payment_proof) }}" alt="Bukti Pembayaran"
                                        class="payment-proof-preview border">
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('customer.orders.uploadPayment', $order) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="transfer_bank"
                                            {{ old('payment_method', $order->payment_method) == 'transfer_bank' ? 'selected' : '' }}>
                                            Transfer Bank
                                        </option>
                                        <option value="e_wallet"
                                            {{ old('payment_method', $order->payment_method) == 'e_wallet' ? 'selected' : '' }}>
                                            E-Wallet (GoPay, OVO, Dana)
                                        </option>
                                        <option value="cash"
                                            {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Cash (Bayar di Tempat)
                                        </option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_proof"
                                        class="form-control @error('payment_proof') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/jpg" required>
                                    <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-upload me-1"></i>
                                        {{ $order->payment_proof ? 'Upload Ulang' : 'Upload Bukti Pembayaran' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Payment Proof (if paid or completed) --}}
            @if (in_array($order->status, ['paid', 'processing', 'shipped', 'completed']) && $order->payment_proof)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-receipt me-2"></i>Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ Storage::url($order->payment_proof) }}" target="_blank">
                            <img src="{{ Storage::url($order->payment_proof) }}" alt="Bukti Pembayaran"
                                class="payment-proof-preview border">
                        </a>
                        <p class="mt-2 mb-0">
                            <span class="badge bg-success">
                                <i class="bx bx-check me-1"></i>Terverifikasi
                            </span>
                        </p>
                    </div>
                </div>
            @endif

            {{-- Customer Notes --}}
            @if ($order->customer_notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-note me-2"></i>Catatan Anda</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->customer_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Order Summary --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nomor Pesanan</label>
                        <p class="mb-0 fw-bold">{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Tanggal Pesanan</label>
                        <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="badge {{ $order->status_badge_class }}">{{ $order->status_display }}</span>
                        </p>
                    </div>
                    @if ($order->payment_method)
                        <div class="mb-3">
                            <label class="text-muted small">Metode Pembayaran</label>
                            <p class="mb-0">
                                @switch($order->payment_method)
                                    @case('transfer_bank')
                                        Transfer Bank
                                    @break

                                    @case('e_wallet')
                                        E-Wallet
                                    @break

                                    @case('cash')
                                        Cash
                                    @break

                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </p>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="text-primary fw-bold fs-5">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Shipping Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-map me-2"></i>Alamat Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p class="fw-bold mb-1">{{ $order->customer_name }}</p>
                    <p class="mb-1">{{ $order->customer_phone }}</p>
                    <p class="mb-1">{{ $order->customer_email }}</p>
                    <hr>
                    <p class="mb-0">{{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- Order Timeline --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-time me-2"></i>Riwayat Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="order-timeline">
                        {{-- Pesanan Dibuat --}}
                        <div class="timeline-item completed">
                            <strong class="d-block">Pesanan Dibuat</strong>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </div>

                        {{-- Persetujuan --}}
                        @if ($order->isRejected())
                            <div class="timeline-item error">
                                <strong class="d-block text-danger">Pesanan Ditolak</strong>
                                <small class="text-muted">{{ $order->rejected_at?->format('d M Y, H:i') ?? '-' }}</small>
                            </div>
                        @elseif($order->approved_at)
                            <div class="timeline-item completed">
                                <strong class="d-block">Pesanan Disetujui</strong>
                                <small class="text-muted">{{ $order->approved_at->format('d M Y, H:i') }}</small>
                                @if ($order->approver)
                                    <br><small class="text-muted">oleh {{ $order->approver->name }}</small>
                                @endif
                            </div>
                        @elseif($order->isPending())
                            <div class="timeline-item active">
                                <strong class="d-block">Menunggu Persetujuan</strong>
                                <small class="text-muted">Sedang diproses</small>
                            </div>
                        @endif

                        {{-- Pembayaran --}}
                        @if (!$order->isRejected() && !$order->isPending())
                            @if ($order->paid_at)
                                <div class="timeline-item completed">
                                    <strong class="d-block">Pembayaran Terverifikasi</strong>
                                    <small class="text-muted">{{ $order->paid_at->format('d M Y, H:i') }}</small>
                                </div>
                            @elseif($order->isApproved())
                                <div class="timeline-item active">
                                    <strong class="d-block">Menunggu Pembayaran</strong>
                                    <small class="text-muted">Silakan upload bukti pembayaran</small>
                                </div>
                            @endif
                        @endif

                        {{-- Pengiriman --}}
                        @if ($order->shipped_at)
                            <div class="timeline-item completed">
                                <strong class="d-block">Pesanan Dikirim</strong>
                                <small class="text-muted">{{ $order->shipped_at->format('d M Y, H:i') }}</small>
                                @if ($order->tracking_number)
                                    <br><small class="text-muted">Resi: {{ $order->tracking_number }}</small>
                                @endif
                            </div>
                        @endif

                        {{-- Selesai --}}
                        @if ($order->completed_at)
                            <div class="timeline-item completed">
                                <strong class="d-block">Pesanan Selesai</strong>
                                <small class="text-muted">{{ $order->completed_at->format('d M Y, H:i') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="card">
                <div class="card-body">
                    @if ($order->isPending())
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 mb-2">
                                <i class="bx bx-x-circle me-1"></i>Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bx bx-arrow-back me-1"></i>Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
