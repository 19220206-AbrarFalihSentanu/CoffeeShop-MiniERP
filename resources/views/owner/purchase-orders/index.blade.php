{{-- File: resources/views/owner/purchase-orders/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Purchase Order Approval')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Purchase Order - Approval</h5>
        <div>
            <span class="badge bg-warning me-2">
                {{ $purchaseOrders->where('status', 'pending')->count() }} Pending
            </span>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending Approval
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                </div>
            </div>
        </form>

        {{-- Alert for pending POs --}}
        @if($purchaseOrders->where('status', 'pending')->count() > 0 && !request('status'))
        <div class="alert alert-warning">
            <i class='bx bx-time-five'></i>
            <strong>Ada {{ $purchaseOrders->where('status', 'pending')->count() }} Purchase Order</strong> yang menunggu approval Anda!
        </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor PO</th>
                        <th>Supplier</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal Submit</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr class="{{ $po->status === 'pending' ? 'table-warning' : '' }}">
                        <td>{{ $loop->iteration + ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() }}</td>
                        <td>
                            <strong>{{ $po->po_number }}</strong>
                            @if($po->status === 'pending')
                                <span class="badge bg-danger ms-1">NEW</span>
                            @endif
                        </td>
                        <td>{{ $po->supplier->name }}</td>
                        <td>{{ $po->creator->name }}</td>
                        <td>
                            @if($po->submitted_at)
                                {{ $po->submitted_at->format('d/m/Y H:i') }}
                                <br><small class="text-muted">{{ $po->submitted_at->diffForHumans() }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <strong>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $po->status_badge_class }}">
                                {{ $po->status_display }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('owner.purchase-orders.show', $po) }}" 
                               class="btn btn-info btn-sm">
                                <i class='bx bx-show'></i> 
                                @if($po->status === 'pending')
                                    Review & Approve
                                @else
                                    Lihat Detail
                                @endif
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            @if(request('status') === 'pending')
                                Tidak ada Purchase Order yang menunggu approval.
                            @else
                                Tidak ada data Purchase Order.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
@endsection