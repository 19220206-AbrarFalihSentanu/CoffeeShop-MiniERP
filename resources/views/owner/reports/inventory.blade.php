{{-- File: resources/views/owner/reports/inventory.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Reports')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-2">
                    <span class="text-muted fw-light">Reports /</span> Inventory
                </h4>
                <p class="text-muted mb-0">
                    View and export inventory reports
                </p>
            </div>
            <div>
                <a href="{{ route('owner.inventory.index') }}" class="btn btn-label-primary">
                    <i class='bx bx-package me-1'></i> Manage Inventory
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Products</h6>
                                <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class='bx bx-box'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Low Stock</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['low_stock'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class='bx bx-error'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Out of Stock</h6>
                                <h3 class="mb-0 text-danger">{{ $stats['out_of_stock'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class='bx bx-x-circle'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Value</h6>
                                <h5 class="mb-0 text-success">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class='bx bx-wallet'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Export -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.reports.inventory') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock Status</label>
                        <select name="stock_status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                Available</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock
                            </option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search me-1'></i> Filter
                        </button>
                        <a href="{{ route('owner.reports.inventory') }}" class="btn btn-label-secondary">
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Export Buttons -->
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted me-2">Export:</span>
                    <a href="{{ route('owner.reports.inventory.export.excel', request()->query()) }}"
                        class="btn btn-sm btn-success me-2">
                        <i class='bx bx-file me-1'></i> Excel
                    </a>
                    <a href="{{ route('owner.reports.inventory.export.pdf', request()->query()) }}"
                        class="btn btn-sm btn-danger">
                        <i class='bx bxs-file-pdf me-1'></i> PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Inventory List</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th class="text-end">Cost Price</th>
                            <th class="text-end">Sell Price</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Reserved</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Min Stock</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $inventory = $product->inventory;
                                $available = $inventory ? $inventory->available : 0;
                                $status = 'ok';

                                if (!$inventory || $available <= 0) {
                                    $status = 'out';
                                } elseif ($available <= $product->min_stock) {
                                    $status = 'low';
                                }
                            @endphp
                            <tr
                                class="{{ $status == 'out' ? 'table-danger' : ($status == 'low' ? 'table-warning' : '') }}">
                                <td>
                                    <code>{{ $product->sku }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class='bx bx-coffee'></i>
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($product->name, 25) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $product->weight }}g</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                                    </span>
                                </td>
                                <td class="text-end">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $inventory ? $inventory->quantity : 0 }}</td>
                                <td class="text-center">{{ $inventory ? $inventory->reserved : 0 }}</td>
                                <td class="text-center">
                                    <strong>{{ $available }}</strong>
                                </td>
                                <td class="text-center">{{ $product->min_stock }}</td>
                                <td class="text-center">
                                    @if ($status == 'ok')
                                        <span class="badge bg-success">OK</span>
                                    @elseif($status == 'low')
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class='bx bx-box bx-lg'></i>
                                        <p class="mt-2">No products found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="card-footer">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
