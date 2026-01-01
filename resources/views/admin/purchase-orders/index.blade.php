{{-- File: resources/views/admin/purchase-orders/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Purchase Orders</h5>
        <div>
            <a href="{{ route('admin.purchase-orders.receive.index') }}" class="btn btn-info btn-sm me-2">
                <i class='bx bx-package'></i> Receive Stock
            </a>
            <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Buat PO Baru
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter & Search --}}
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nomor PO atau supplier..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-search'></i> Cari
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor PO</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td>{{ $loop->iteration + ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() }}</td>
                        <td>
                            <strong>{{ $po->po_number }}</strong>
                        </td>
                        <td>{{ $po->supplier->name }}</td>
                        <td>{{ $po->created_at->format('d/m/Y') }}</td>
                        <td>
                            <strong>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $po->status_badge_class }}">
                                {{ $po->status_display }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" 
                                        data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-vertical-rounded'></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.purchase-orders.show', $po) }}">
                                        <i class='bx bx-show me-1'></i> Lihat Detail
                                    </a>

                                    @if($po->status === 'draft')
                                        <a class="dropdown-item" href="{{ route('admin.purchase-orders.edit', $po) }}">
                                            <i class='bx bx-edit me-1'></i> Edit
                                        </a>
                                        <form action="{{ route('admin.purchase-orders.submit', $po) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Submit PO ini untuk approval?')">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class='bx bx-send me-1'></i> Submit untuk Approval
                                            </button>
                                        </form>
                                    @endif

                                    @if($po->status === 'rejected')
                                        <a class="dropdown-item" href="{{ route('admin.purchase-orders.edit', $po) }}">
                                            <i class='bx bx-edit me-1'></i> Edit & Submit Ulang
                                        </a>
                                    @endif

                                    @if($po->canDelete())
                                        <form action="{{ route('admin.purchase-orders.destroy', $po) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus PO ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class='bx bx-trash me-1'></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data Purchase Order.</td>
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