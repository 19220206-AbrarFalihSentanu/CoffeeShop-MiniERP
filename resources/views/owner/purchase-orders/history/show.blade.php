{{-- File: resources/views/owner/purchase-orders/history/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Purchase Order - ' . $purchaseOrder->po_number)

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('owner.purchase-orders.history.index') }}">{{ __('purchase_orders.po_history') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $purchaseOrder->po_number }}</li>
        </ol>
    </nav>

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
                            <a href="{{ route('owner.purchase-orders.history.index') }}" class="btn btn-secondary btn-sm">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <p class="mb-2"><strong>Nama:</strong><br>{{ $purchaseOrder->supplier->name ?? '-' }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Tipe:</strong><br>{{ $purchaseOrder->supplier->type_display ?? '-' }}</p>
                                    <p class="mb-2"><strong>Contact
                                            Person:</strong><br>{{ $purchaseOrder->supplier->contact_person ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Email:</strong><br>{{ $purchaseOrder->supplier->email ?? '-' }}</p>
                                    <p class="mb-2">
                                        <strong>Phone:</strong><br>{{ $purchaseOrder->supplier->phone ?? '-' }}</p>
                                    <p class="mb-2">
                                        <strong>Alamat:</strong><br>{{ $purchaseOrder->supplier->full_address ?? '-' }}</p>
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
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrder->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->product->name ?? $item->product_name }}</strong>
                                                    @if ($item->product)
                                                        <br><small class="text-muted">SKU:
                                                            {{ $item->product->sku }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-end">{{ number_format($item->quantity, 2) }}
                                                    {{ $item->unit ?? 'kg' }}</td>
                                                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end">
                                                <strong class="text-primary">
                                                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                                                </strong>
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
                </div>

                {{-- Right Column --}}
                <div class="col-md-4">
                    {{-- PO Info --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi PO</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>No. PO:</strong><br>{{ $purchaseOrder->po_number }}</p>
                            <p class="mb-2"><strong>Status:</strong><br>
                                <span class="badge {{ $purchaseOrder->status_badge_class }}">
                                    {{ $purchaseOrder->status_display }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Dibuat:</strong><br>{{ $purchaseOrder->created_at->format('d M Y, H:i') }}</p>
                            <p class="mb-2"><strong>Oleh:</strong><br>{{ $purchaseOrder->creator->name ?? '-' }}</p>
                            @if ($purchaseOrder->approved_at)
                                <p class="mb-2">
                                    <strong>Disetujui:</strong><br>{{ $purchaseOrder->approved_at->format('d M Y, H:i') }}
                                </p>
                                <p class="mb-2"><strong>Oleh:</strong><br>{{ $purchaseOrder->approver->name ?? '-' }}</p>
                            @endif
                            @if ($purchaseOrder->received_at)
                                <p class="mb-2">
                                    <strong>Diterima:</strong><br>{{ $purchaseOrder->received_at->format('d M Y, H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-white">Ringkasan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah Item:</span>
                                <strong>{{ $purchaseOrder->items->count() }} item(s)</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Qty:</span>
                                <strong>{{ number_format($purchaseOrder->items->sum('quantity'), 2) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5 mb-0">Total:</span>
                                <strong class="text-primary h5 mb-0">
                                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
