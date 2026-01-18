{{-- File: resources/views/owner/payments/show.blade.php --}}
{{-- SAMA seperti admin/payments/show.blade.php, hanya beda route --}}

@extends('layouts.app')

@section('title', 'Detail Pembayaran - ' . $payment->order->order_number)

@push('styles')
    <style>
        .payment-proof-image {
            max-width: 100%;
            max-height: 600px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .payment-proof-image:hover {
            transform: scale(1.02);
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('owner.payments.index') }}">Verifikasi Pembayaran</a></li>
            <li class="breadcrumb-item active">{{ $payment->order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Left Column - Payment Proof --}}
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-image me-2"></i>Bukti Pembayaran</h5>
                </div>
                <div class="card-body text-center">
                    @if ($payment->payment_proof)
                        <a href="{{ Storage::url($payment->payment_proof) }}" target="_blank">
                            <img src="{{ Storage::url($payment->payment_proof) }}" alt="Bukti Pembayaran"
                                class="payment-proof-image">
                        </a>
                        <p class="mt-3 mb-0">
                            <small class="text-muted">Klik gambar untuk memperbesar</small>
                        </p>
                    @else
                        <div class="py-5">
                            <i class="bx bx-image-alt bx-lg text-muted"></i>
                            <p class="text-muted mb-0">Tidak ada bukti pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-package me-2"></i>Detail Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment->order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end">Subtotal</td>
                                    <td class="text-end">Rp {{ number_format($payment->order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($payment->order->tax_amount > 0)
                                    <tr>
                                        <td colspan="2" class="text-end">Pajak
                                            ({{ number_format($payment->order->tax_rate, 1) }}%)</td>
                                        <td class="text-end">Rp
                                            {{ number_format($payment->order->tax_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($payment->order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="2" class="text-end">Ongkir</td>
                                        <td class="text-end">Rp
                                            {{ number_format($payment->order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end">
                                        <strong class="text-primary">
                                            Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Payment Info & Actions --}}
        <div class="col-lg-5">
            {{-- Payment Status Alert --}}
            @if ($payment->isPending())
                <div class="alert alert-warning mb-4">
                    <h6 class="alert-heading"><i class="bx bx-time me-1"></i>Menunggu Verifikasi</h6>
                    <p class="mb-0">Pembayaran ini perlu diverifikasi. Periksa bukti pembayaran dengan teliti sebelum
                        memverifikasi.</p>
                </div>
            @elseif($payment->isVerified())
                <div class="alert alert-success mb-4">
                    <h6 class="alert-heading"><i class="bx bx-check-circle me-1"></i>Sudah Diverifikasi</h6>
                    <p class="mb-0">
                        Diverifikasi oleh <strong>{{ $payment->verifier->name }}</strong><br>
                        pada {{ $payment->verified_at->format('d M Y, H:i') }}
                    </p>
                </div>
            @elseif($payment->isRejected())
                <div class="alert alert-danger mb-4">
                    <h6 class="alert-heading"><i class="bx bx-x-circle me-1"></i>Ditolak</h6>
                    <p class="mb-2">
                        Ditolak oleh <strong>{{ $payment->verifier->name }}</strong><br>
                        pada {{ $payment->rejected_at->format('d M Y, H:i') }}
                    </p>
                    <p class="mb-0"><strong>Alasan:</strong> {{ $payment->rejection_reason }}</p>
                </div>
            @endif

            {{-- Payment Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Order Number</label>
                        <p class="mb-0 fw-bold">{{ $payment->order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Customer</label>
                        <p class="mb-0">{{ $payment->order->customer_name }}</p>
                        <small class="text-muted">{{ $payment->order->customer_email }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Jumlah Pembayaran</label>
                        <p class="mb-0 fw-bold text-primary fs-5">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Metode Pembayaran</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $payment->payment_method_display }}</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Upload Pada</label>
                        <p class="mb-0">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($payment->customer_notes)
                        <div class="mb-3">
                            <label class="text-muted small">Catatan Customer</label>
                            <p class="mb-0">{{ $payment->customer_notes }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="badge {{ $payment->status_badge_class }}">
                                {{ $payment->status_display }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            @if ($payment->canVerify())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-check-shield me-2"></i>Aksi Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('owner.payments.verify', $payment) }}" method="POST"
                            data-confirm="Pembayaran akan diverifikasi dan pesanan akan dilanjutkan."
                            data-confirm-title="Verifikasi Pembayaran?" data-confirm-icon="question"
                            data-confirm-button="Ya, Verifikasi!">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bx bx-check-circle me-1"></i>Verifikasi Pembayaran
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="bx bx-x-circle me-1"></i>Tolak Pembayaran
                        </button>
                    </div>
                </div>
            @endif

            {{-- Order Processing Actions --}}
            @if ($payment->isVerified())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-package me-2"></i>Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        {{-- Current Status Display --}}
                        <div class="alert alert-{{ $payment->order->status === 'completed' ? 'success' : 'info' }} mb-3">
                            <strong>Status:</strong> {{ $payment->order->status_display }}
                            @if ($payment->order->tracking_number)
                                <br><small>Resi: {{ $payment->order->tracking_number }}</small>
                            @endif
                        </div>

                        {{-- Step 1: Process Order (paid -> processing) --}}
                        @if ($payment->order->canProcess())
                            <form action="{{ route('owner.payments.processOrder', $payment) }}" method="POST"
                                data-confirm="Pesanan akan ditandai sedang diproses/dikemas."
                                data-confirm-title="Proses Pesanan?" data-confirm-icon="question"
                                data-confirm-button="Ya, Proses!">
                                @csrf
                                <button type="submit" class="btn btn-info w-100 mb-2">
                                    <i class="bx bx-box me-1"></i>Proses Pesanan (Kemas)
                                </button>
                            </form>
                            <small class="text-muted d-block mb-3">
                                Klik untuk menandai pesanan sedang diproses/dikemas.
                            </small>
                        @endif

                        {{-- Step 2: Ship Order (processing -> shipped) --}}
                        @if ($payment->order->canShip())
                            <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#shipModal">
                                <i class="bx bx-truck me-1"></i>Kirim Pesanan
                            </button>
                            <small class="text-muted d-block mb-3">
                                Customer akan menerima email notifikasi pengiriman.
                            </small>
                        @endif

                        {{-- Step 3: Complete Order (shipped -> completed) --}}
                        @if ($payment->order->canComplete())
                            <form action="{{ route('owner.payments.completeOrder', $payment) }}" method="POST"
                                data-confirm="Pesanan akan ditandai selesai." data-confirm-title="Pesanan Selesai?"
                                data-confirm-icon="question" data-confirm-button="Ya, Selesai!">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bx bx-check-double me-1"></i>Pesanan Selesai (Diterima)
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">
                                Tandai setelah customer menerima barang.
                            </small>
                        @endif

                        {{-- Already Completed --}}
                        @if ($payment->order->isCompleted())
                            <div class="text-center py-2">
                                <i class="bx bx-check-circle text-success bx-lg"></i>
                                <p class="mb-0 mt-2 text-success fw-bold">Pesanan Selesai</p>
                                <small
                                    class="text-muted">{{ $payment->order->completed_at?->format('d M Y, H:i') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Invoice Section --}}
            @if (in_array($payment->order->status, ['approved', 'paid', 'processing', 'shipped', 'completed']))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bx bx-file-blank me-2"></i>Invoice</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <a href="{{ route('invoices.preview', $payment->order) }}" target="_blank"
                                class="btn btn-outline-primary flex-fill">
                                <i class="bx bx-show me-1"></i>Lihat
                            </a>
                            <a href="{{ route('invoices.download', $payment->order) }}"
                                class="btn btn-primary flex-fill">
                                <i class="bx bx-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('owner.payments.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Ship Modal --}}
    <div class="modal fade" id="shipModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('owner.payments.shipOrder', $payment) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bx bx-truck me-2"></i>Kirim Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-1"></i>
                            Customer akan menerima email notifikasi pengiriman dengan nomor resi.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Resi / Tracking <span
                                    class="text-muted">(Opsional)</span></label>
                            <input type="text" name="tracking_number" class="form-control"
                                placeholder="Contoh: JNE1234567890">
                            <small class="text-muted">Isi jika menggunakan jasa ekspedisi</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Pengiriman <span
                                    class="text-muted">(Opsional)</span></label>
                            <textarea name="shipping_notes" class="form-control" rows="2" placeholder="Catatan untuk internal..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-truck me-1"></i>Kirim Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('owner.payments.reject', $payment) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bx bx-info-circle me-1"></i>
                            Customer akan dapat mengupload ulang bukti pembayaran setelah ditolak.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan..."
                                required></textarea>
                            <small class="text-muted">Alasan akan dikirimkan ke customer via email.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-x-circle me-1"></i>Tolak Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

