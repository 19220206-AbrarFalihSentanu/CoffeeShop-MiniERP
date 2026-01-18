{{-- File: resources/views/admin/purchase-orders/receive/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Receive Stock dari Purchase Order')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Receive Stock dari Purchase Order</h5>
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                <i class='bx bx-arrow-back'></i> Kembali ke PO List
            </a>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <i class='bx bx-info-circle'></i>
                Halaman ini menampilkan Purchase Order yang sudah <strong>Approved</strong> dan siap untuk diterima
                barangnya.
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomor PO</th>
                            <th>Supplier</th>
                            <th>Approved Date</th>
                            <th>Expected Delivery</th>
                            <th>Total Amount</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $po)
                            <tr>
                                <td>{{ $loop->iteration + ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() }}
                                </td>
                                <td>
                                    <strong>{{ $po->po_number }}</strong>
                                </td>
                                <td>{{ $po->supplier->name }}</td>
                                <td>{{ $po->approved_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($po->expected_delivery_date->isPast())
                                        <span class="badge bg-danger">
                                            {{ $po->expected_delivery_date->format('d/m/Y') }} (Terlambat)
                                        </span>
                                    @else
                                        {{ $po->expected_delivery_date->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px; min-width: 100px;">
                                        <div class="progress-bar {{ $po->receive_progress >= 100 ? 'bg-success' : 'bg-info' }}"
                                            role="progressbar" style="width: {{ $po->receive_progress }}%">
                                            {{ number_format($po->receive_progress, 0) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $po->status_badge_class }}">
                                        {{ $po->status_display }}
                                    </span>
                                </td>
                                <td>
                                    @if ($po->status === 'approved')
                                        <a href="{{ route('admin.purchase-orders.receive.show', $po) }}"
                                            class="btn btn-info btn-sm">
                                            <i class='bx bx-package'></i> Receive Stock
                                        </a>
                                    @else
                                        <span class="badge bg-success">
                                            <i class='bx bx-check'></i> Completed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    Tidak ada Purchase Order yang siap untuk di-receive.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <x-pagination-with-info :paginator="$purchaseOrders" />
        </div>
    </div>
@endsection

