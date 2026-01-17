{{-- File: resources/views/owner/purchase-orders/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Purchase Order - ' . $purchaseOrder->po_number)

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- Header Card --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-2">{{ $purchaseOrder->po_number }}</h4>
                            <span class="badge {{ $purchaseOrder->status_badge_class }} fs-6">
                                {{ $purchaseOrder->status_display }}
                            </span>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('owner.purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>

                            @if ($purchaseOrder->status === 'pending')
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#approveModal">
                                    <i class='bx bx-check-circle'></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    <i class='bx bx-x-circle'></i> Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert for Pending --}}
            @if ($purchaseOrder->status === 'pending')
                <div class="alert alert-warning">
                    <i class='bx bx-time-five'></i>
                    <strong>Purchase Order ini menunggu approval Anda!</strong><br>
                    Silakan review detail di bawah ini dan putuskan untuk Approve atau Reject.
                </div>
            @endif

            {{-- Rejection Reason Alert --}}
            @if ($purchaseOrder->status === 'rejected' && $purchaseOrder->rejection_reason)
                <div class="alert alert-danger">
                    <h6 class="alert-heading"><i class='bx bx-error'></i> Alasan Penolakan:</h6>
                    <p class="mb-0">{{ $purchaseOrder->rejection_reason }}</p>
                </div>
            @endif

            <div class="row">
                {{-- Left Column --}}
                <div class="col-md-8">
                    {{-- Supplier Info --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi Supplier</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Nama:</strong><br>{{ $purchaseOrder->supplier->name }}</p>
                                    <p class="mb-2"><strong>Tipe:</strong><br>{{ $purchaseOrder->supplier->type_display }}
                                    </p>
                                    <p class="mb-2"><strong>Contact
                                            Person:</strong><br>{{ $purchaseOrder->supplier->contact_person }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Email:</strong><br>{{ $purchaseOrder->supplier->email }}</p>
                                    <p class="mb-2"><strong>Phone:</strong><br>{{ $purchaseOrder->supplier->phone }}</p>
                                    <p class="mb-2">
                                        <strong>Alamat:</strong><br>{{ $purchaseOrder->supplier->full_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Detail Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th class="text-end">Qty (kg)</th>
                                            <th class="text-end">Harga/kg</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrder->items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->product->category->name }}</td>
                                                <td class="text-end">{{ number_format($item->quantity_ordered) }}</td>
                                                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                            <td class="text-end"><strong>Rp
                                                    {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Pajak
                                                    ({{ setting('tax_rate', 11) }}%):</strong></td>
                                            <td class="text-end"><strong>Rp
                                                    {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="4" class="text-end">
                                                <h5 class="mb-0">TOTAL:</h5>
                                            </td>
                                            <td class="text-end">
                                                <h5 class="mb-0 text-success">Rp
                                                    {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</h5>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Notes from Admin --}}
                    @if ($purchaseOrder->notes)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Catatan dari Admin</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $purchaseOrder->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column - Info --}}
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi PO</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Dibuat oleh:</strong><br>
                                {{ $purchaseOrder->creator->name }}<br>
                                <small class="text-muted">{{ $purchaseOrder->creator->email }}</small>
                            </p>
                            <p class="mb-2">
                                <strong>Tanggal dibuat:</strong><br>
                                {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                            </p>

                            @if ($purchaseOrder->submitted_at)
                                <p class="mb-2">
                                    <strong>Disubmit:</strong><br>
                                    {{ $purchaseOrder->submitted_at->format('d/m/Y H:i') }}<br>
                                    <small class="text-muted">{{ $purchaseOrder->submitted_at->diffForHumans() }}</small>
                                </p>
                            @endif

                            <p class="mb-2">
                                <strong>Expected Delivery:</strong><br>
                                {{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}
                                @if ($purchaseOrder->expected_delivery_date->isPast())
                                    <br><span class="badge bg-danger">Terlambat</span>
                                @endif
                            </p>

                            @if ($purchaseOrder->approved_at)
                                <hr>
                                <p class="mb-2">
                                    <strong>Disetujui oleh:</strong><br>
                                    {{ $purchaseOrder->approver->name }}
                                </p>
                                <p class="mb-2">
                                    <strong>Tanggal disetujui:</strong><br>
                                    {{ $purchaseOrder->approved_at->format('d/m/Y H:i') }}
                                </p>
                            @endif

                            @if ($purchaseOrder->rejected_at)
                                <hr>
                                <p class="mb-2">
                                    <strong>Ditolak oleh:</strong><br>
                                    {{ $purchaseOrder->approver->name }}
                                </p>
                                <p class="mb-0">
                                    <strong>Tanggal ditolak:</strong><br>
                                    {{ $purchaseOrder->rejected_at->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Financial Impact --}}
                    @if ($purchaseOrder->status === 'pending')
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">💰 Dampak Keuangan</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    Jika PO ini disetujui, akan tercatat sebagai <strong>Pengeluaran</strong> sebesar:
                                </p>
                                <h4 class="text-danger mb-0">
                                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">Kategori: Pembelian Stok</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class='bx bx-check-circle'></i> Approve Purchase Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('owner.purchase-orders.approve', $purchaseOrder) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            Dengan menyetujui Purchase Order ini, Anda mengizinkan pembelian stok dengan total:
                        </div>
                        <div class="text-center my-3">
                            <h3 class="text-success">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
                        <p><strong>Total Items:</strong> {{ $purchaseOrder->items->count() }} item</p>
                        <p><strong>Expected Delivery:</strong>
                            {{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}</p>

                        <hr>
                        <p class="mb-0 text-muted">
                            <small>
                                <i class='bx bx-info-circle'></i>
                                Setelah approve, admin akan melakukan pemesanan ke supplier dan melakukan receive stock saat
                                barang tiba.
                            </small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class='bx bx-check'></i> Ya, Approve PO Ini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class='bx bx-x-circle'></i> Reject Purchase Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('owner.purchase-orders.reject', $purchaseOrder) }}" method="POST"
                    id="rejectForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <i class='bx bx-error'></i>
                            Anda akan menolak Purchase Order: <strong>{{ $purchaseOrder->po_number }}</strong>
                        </div>

                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('rejection_reason') is-invalid @enderror" id="rejection_reason"
                                name="rejection_reason" rows="4" required
                                placeholder="Jelaskan alasan penolakan secara detail agar admin dapat memahami dan memperbaiki PO...">{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 10 karakter</small>
                        </div>

                        <p class="mb-0 text-muted">
                            <small>
                                <i class='bx bx-info-circle'></i>
                                Admin akan menerima email notifikasi berisi alasan penolakan dan dapat mengedit PO untuk
                                submit ulang.
                            </small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class='bx bx-x'></i> Ya, Reject PO Ini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Validate rejection reason length
        document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
            const reason = document.getElementById('rejection_reason').value.trim();
            if (reason.length < 10) {
                e.preventDefault();
                swalCoffee.fire({
                    title: 'Alasan Terlalu Pendek',
                    text: 'Alasan penolakan harus minimal 10 karakter!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });
    </script>
@endpush
