{{-- File: resources/views/owner/inventory/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Inventory')

@section('content')
    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-package rounded bx-md text-primary"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Produk</span>
                    <h3 class="card-title mb-2">{{ $stats['total_products'] }}</h3>
                    <small class="text-muted">Produk dengan inventory</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-error rounded bx-md text-warning"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Stok Menipis</span>
                    <h3 class="card-title text-warning mb-2">{{ $stats['low_stock'] }}</h3>
                    <small class="text-muted">Perlu restocking</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-x-circle rounded bx-md text-danger"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Stok Habis</span>
                    <h3 class="card-title text-danger mb-2">{{ $stats['out_of_stock'] }}</h3>
                    <small class="text-muted">Segera restocking!</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-dollar-circle rounded bx-md text-success"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Nilai Stok</span>
                    <h3 class="card-title text-nowrap mb-2">
                        Rp {{ number_format($stats['total_value'], 0, ',', '.') }}
                    </h3>
                    <small class="text-muted">Berdasarkan HPP</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-package me-2"></i>Inventory Management
            </h5>
            <div>
                <a href="{{ route('admin.inventory.alerts') }}" class="btn btn-warning btn-sm me-2">
                    <i class="bx bx-bell me-1"></i>Stock Alerts
                    @if ($stats['low_stock'] + $stats['out_of_stock'] > 0)
                        <span class="badge bg-danger ms-1">{{ $stats['low_stock'] + $stats['out_of_stock'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.inventory.bulkAdjust') }}" class="btn btn-info btn-sm me-2">
                    <i class="bx bx-spreadsheet me-1"></i>Bulk Adjustment
                </a>
                <a href="{{ route('admin.inventory.logs') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-history me-1"></i>View History
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Filter & Search --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="stock_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Status Stok</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                Stok Aman
                            </option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                Stok Menipis
                            </option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>
                                Stok Habis
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari produk atau SKU..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bx bx-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px">Gambar</th>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Stok Total</th>
                            <th>Reserved</th>
                            <th>Tersedia</th>
                            <th>Min. Stok</th>
                            <th>Nilai Stok</th>
                            <th>Status</th>
                            <th style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="{{ $product->isLowStock() ? 'table-warning' : '' }}">
                                <td>
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bx bx-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->weight }}g</small>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    <strong>{{ $product->inventory ? $product->inventory->quantity : 0 }}</strong>
                                </td>
                                <td>
                                    {{ $product->inventory ? $product->inventory->reserved : 0 }}
                                </td>
                                <td>
                                    @if ($product->inventory)
                                        @if ($product->inventory->available <= 0)
                                            <span class="badge bg-danger">0</span>
                                        @elseif($product->isLowStock())
                                            <span class="badge bg-warning">
                                                {{ $product->inventory->available }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $product->inventory->available }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $product->min_stock }}</td>
                                <td>
                                    <small class="text-muted">
                                        Rp
                                        {{ number_format($product->cost_price * ($product->inventory ? $product->inventory->available : 0), 0, ',', '.') }}
                                    </small>
                                </td>
                                <td>
                                    @if ($product->inventory)
                                        @if ($product->inventory->available <= 0)
                                            <span class="badge bg-danger">
                                                <i class="bx bx-x-circle"></i> Habis
                                            </span>
                                        @elseif($product->isLowStock())
                                            <span class="badge bg-warning">
                                                <i class="bx bx-error"></i> Menipis
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bx bx-check-circle"></i> Aman
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.inventory.adjust', $product) }}"
                                            class="btn btn-sm btn-primary" title="Adjust Stok">
                                            <i class="bx bx-adjust"></i>
                                        </a>
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="bx bx-package bx-lg text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada data inventory</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <x-pagination-with-info :paginator="$products" />
        </div>
    </div>
@endsection

