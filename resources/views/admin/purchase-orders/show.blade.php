{{-- File: resources/views/admin/purchase-orders/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Purchase Order')

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
                            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>

                            @if ($purchaseOrder->status === 'draft')
                                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit'></i> Edit
                                </a>
                                <form action="{{ route('admin.purchase-orders.submit', $purchaseOrder) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Submit PO ini untuk approval Owner?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class='bx bx-send'></i> Submit untuk Approval
                                    </button>
                                </form>
                            @endif

                            @if ($purchaseOrder->status === 'rejected')
                                {{-- TAMBAHKAN INI --}}
                                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit'></i> Edit PO
                                </a>

                                {{-- Optional: Direct submit jika tidak perlu edit --}}
                                <form action="{{ route('admin.purchase-orders.submit', $purchaseOrder) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Submit ulang PO ini tanpa perubahan?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class='bx bx-send'></i> Submit Ulang
                                    </button>
                                </form>
                            @endif

                            @if ($purchaseOrder->status === 'approved')
                                <a href="{{ route('admin.purchase-orders.receive.show', $purchaseOrder) }}"
                                    class="btn btn-info btn-sm">
                                    <i class='bx bx-package'></i> Receive Stock
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Left Column - PO Info --}}
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
                                    <p class="mb-2">
                                        <strong>Tipe:</strong><br>{{ $purchaseOrder->supplier->type_display }}</p>
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
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                            <td class="text-end">
                                                <h5 class="mb-0 text-primary">Rp
                                                    {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</h5>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if ($purchaseOrder->notes)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Catatan</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $purchaseOrder->notes }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Rejection Reason --}}
                    @if ($purchaseOrder->status === 'rejected' && $purchaseOrder->rejection_reason)
                        <div class="card border-danger mb-3">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Alasan Penolakan</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-danger">{{ $purchaseOrder->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column - Status & Timeline --}}
                <div class="col-md-4">
                    {{-- Status Info --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi PO</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Dibuat oleh:</strong><br>
                                {{ $purchaseOrder->creator->name }}
                            </p>
                            <p class="mb-2">
                                <strong>Tanggal dibuat:</strong><br>
                                {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-2">
                                <strong>Expected Delivery:</strong><br>
                                {{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}
                            </p>

                            @if ($purchaseOrder->submitted_at)
                                <hr>
                                <p class="mb-2">
                                    <strong>Disubmit:</strong><br>
                                    {{ $purchaseOrder->submitted_at->format('d/m/Y H:i') }}
                                </p>
                            @endif

                            @if ($purchaseOrder->approved_at)
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
                                <p class="mb-2">
                                    <strong>Ditolak oleh:</strong><br>
                                    {{ $purchaseOrder->approver->name }}
                                </p>
                                <p class="mb-2">
                                    <strong>Tanggal ditolak:</strong><br>
                                    {{ $purchaseOrder->rejected_at->format('d/m/Y H:i') }}
                                </p>
                            @endif

                            @if ($purchaseOrder->completed_at)
                                <p class="mb-0">
                                    <strong>Selesai:</strong><br>
                                    {{ $purchaseOrder->completed_at->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Receive Progress (if approved) --}}
                    @if (in_array($purchaseOrder->status, ['approved', 'completed']))
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Progress Penerimaan</h5>
                            </div>
                            <div class="card-body">
                                <div class="progress mb-2" style="height: 25px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $purchaseOrder->receive_progress }}%">
                                        {{ number_format($purchaseOrder->receive_progress, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    @if ($purchaseOrder->status === 'completed')
                                        ✅ Semua item telah diterima
                                    @else
                                        Sebagian item sudah diterima
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
