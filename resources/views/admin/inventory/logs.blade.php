{{-- File: resources/views/owner/inventory/logs.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Inventory')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-history me-2"></i>Riwayat Perubahan Inventory
            </h5>
            <div>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
                <a href="{{ route('admin.inventory.export') }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download me-1"></i>Export
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Advanced Filters --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label small">Produk</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">Semua Produk</option>
                            @foreach ($products as $prod)
                                <option value="{{ $prod->id }}"
                                    {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->name }} ({{ $prod->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Tipe</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Semua Tipe</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>
                                Adjustment
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Reference/Notes..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="width: 140px">Tanggal & Waktu</th>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th style="width: 100px">Tipe</th>
                            <th style="width: 80px">Jumlah</th>
                            <th style="width: 80px">Before</th>
                            <th style="width: 80px">After</th>
                            <th>User</th>
                            <th>Reference</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small>
                                        {{ $log->created_at->format('d/m/Y') }}
                                        <br>
                                        <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $log->product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $log->product->weight }}g</small>
                                </td>
                                <td>
                                    <code class="small">{{ $log->product->sku }}</code>
                                </td>
                                <td>
                                    @if ($log->type === 'in')
                                        <span class="badge bg-success">
                                            <i class="bx bx-plus-circle"></i> Stock In
                                        </span>
                                    @elseif($log->type === 'out')
                                        <span class="badge bg-danger">
                                            <i class="bx bx-minus-circle"></i> Stock Out
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bx bx-adjust"></i> Adjustment
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                    </strong>
                                </td>
                                <td>{{ $log->before }}</td>
                                <td>
                                    <strong>{{ $log->after }}</strong>
                                </td>
                                <td>
                                    <small>
                                        {{ $log->user->name }}
                                        <br>
                                        <span class="text-muted">{{ $log->user->role->display_name }}</span>
                                    </small>
                                </td>
                                <td>
                                    @if ($log->reference)
                                        <code class="small">{{ $log->reference }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->notes ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bx bx-history bx-lg text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada riwayat inventory</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $logs->appends(request()->query())->links() }}
            </div>

            {{-- Summary Info --}}
            @if ($logs->count() > 0)
                <div class="alert alert-info mt-3">
                    <i class="bx bx-info-circle me-1"></i>
                    Menampilkan <strong>{{ $logs->count() }}</strong> dari <strong>{{ $logs->total() }}</strong>
                    riwayat transaksi
                </div>
            @endif
        </div>
    </div>
@endsection
