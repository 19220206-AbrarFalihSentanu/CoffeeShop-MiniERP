{{-- File: resources/views/admin/products/show.blade.php --}}

@extends('layouts.app')

@section('title', __('products.product_details'))

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            {{-- Header Actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }}
                    </a>
                </div>
                <div>
                    <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>{{ __('products.edit_product') }}
                    </a>
                    <form action="{{ route('owner.products.destroy', $product) }}" method="POST" class="d-inline"
                        data-confirm="{{ __('products.confirm_delete_product') }}" data-confirm-title="Hapus Produk?"
                        data-confirm-icon="warning" data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bx bx-trash me-1"></i>{{ __('general.delete') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                {{-- Product Info Card --}}
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="mb-2">{{ $product->name }}</h4>
                                    <div class="mb-2">
                                        <span class="badge bg-label-primary me-2">
                                            <i class="bx bx-barcode me-1"></i>{{ $product->sku }}
                                        </span>
                                        <span class="badge bg-label-info me-2">
                                            {{ $product->category->name }}
                                        </span>
                                        @if ($product->type === 'whole_bean')
                                            <span class="badge bg-label-secondary">
                                                <i class="bx bx-coffee-bean me-1"></i>Whole Bean
                                            </span>
                                        @elseif($product->type === 'ground')
                                            <span class="badge bg-label-secondary">
                                                <i class="bx bx-coffee me-1"></i>Ground
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">
                                                <i class="bx bx-coffee-togo me-1"></i>Instant
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }} mb-2">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    @if ($product->is_featured)
                                        <br>
                                        <span class="badge bg-warning">
                                            <i class="bx bx-star me-1"></i>Featured
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-primary">
                                        <i class="bx bx-info-circle me-1"></i>Informasi Produk
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" style="width: 120px">Berat</td>
                                            <td>: <strong>{{ $product->weight }}g</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Harga Modal (HPP)</td>
                                            <td>: <strong class="text-info">Rp
                                                    {{ number_format($product->cost_price, 0, ',', '.') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Harga Normal</td>
                                            <td>: <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Margin Keuntungan</td>
                                            <td>:
                                                @php
                                                    $margin =
                                                        (($product->price - $product->cost_price) /
                                                            $product->cost_price) *
                                                        100;
                                                @endphp
                                                <strong
                                                    class="{{ $margin > 50 ? 'text-success' : ($margin > 30 ? 'text-warning' : 'text-danger') }}">
                                                    {{ number_format($margin, 2) }}%
                                                </strong>
                                                <br>
                                                <small class="text-muted">Rp
                                                    {{ number_format($product->price - $product->cost_price, 0, ',', '.') }}</small>
                                            </td>
                                        </tr>
                                        @if ($product->isDiscountActive())
                                            <tr>
                                                <td class="text-muted">Diskon</td>
                                                <td>:
                                                    <span class="badge bg-danger">
                                                        -{{ number_format($product->discount_percentage, 0) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Harga Diskon</td>
                                                <td>: <strong class="text-success">Rp
                                                        {{ number_format($product->final_price, 0, ',', '.') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Hemat</td>
                                                <td>: <strong class="text-danger">Rp
                                                        {{ number_format($product->savings_amount, 0, ',', '.') }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Periode Diskon</td>
                                                <td>:
                                                    @if ($product->discount_start_date)
                                                        {{ $product->discount_start_date->format('d/m/Y') }}
                                                    @else
                                                        Sekarang
                                                    @endif
                                                    -
                                                    @if ($product->discount_end_date)
                                                        {{ $product->discount_end_date->format('d/m/Y') }}
                                                    @else
                                                        Tidak terbatas
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-primary">
                                        <i class="bx bx-package me-1"></i>Informasi Stok
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" style="width: 140px">Stok Total</td>
                                            <td>: <strong>{{ $product->inventory ? $product->inventory->quantity : 0 }}
                                                    unit</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Stok Reserved</td>
                                            <td>: {{ $product->inventory ? $product->inventory->reserved : 0 }} unit</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Stok Tersedia</td>
                                            <td>:
                                                <strong
                                                    class="{{ $product->isLowStock() ? 'text-warning' : 'text-success' }}">
                                                    {{ $product->getAvailableStock() }} unit
                                                </strong>
                                                @if ($product->isLowStock())
                                                    <span class="badge bg-warning ms-1">Low Stock!</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Min. Stok Alert</td>
                                            <td>: {{ $product->min_stock }} unit</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if ($product->description)
                                <hr>
                                <h6 class="text-primary">
                                    <i class="bx bx-detail me-1"></i>Deskripsi Produk
                                </h6>
                                <p class="text-muted">{{ $product->description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Inventory Logs --}}
                    @if ($product->inventoryLogs->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bx bx-history me-1"></i>Riwayat Perubahan Stok (5 Terakhir)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Tipe</th>
                                                <th>Jumlah</th>
                                                <th>Before</th>
                                                <th>After</th>
                                                <th>User</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->inventoryLogs->take(5) as $log)
                                                <tr>
                                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @if ($log->type === 'in')
                                                            <span class="badge bg-success">Stock In</span>
                                                        @elseif($log->type === 'out')
                                                            <span class="badge bg-danger">Stock Out</span>
                                                        @else
                                                            <span class="badge bg-warning">Adjustment</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong
                                                            class="{{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                                        </strong>
                                                    </td>
                                                    <td>{{ $log->before }}</td>
                                                    <td>{{ $log->after }}</td>
                                                    <td>{{ $log->user->name }}</td>
                                                    <td>
                                                        <small class="text-muted">{{ $log->notes ?? '-' }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($product->inventoryLogs->count() > 5)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            Menampilkan 5 dari {{ $product->inventoryLogs->count() }} riwayat
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-md-4">
                    {{-- Product Image --}}
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 300px;">
                                    <div class="text-center">
                                        <i class="bx bx-image bx-lg text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <form action="{{ route('owner.products.toggleStatus', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-{{ $product->is_active ? 'danger' : 'success' }} w-100">
                                        <i class="bx bx-{{ $product->is_active ? 'x' : 'check' }}-circle me-1"></i>
                                        {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Produk
                                    </button>
                                </form>

                                <form action="{{ route('owner.products.toggleFeatured', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning w-100">
                                        <i class="bx bx-star me-1"></i>
                                        {{ $product->is_featured ? 'Hapus dari' : 'Jadikan' }} Featured
                                    </button>
                                </form>

                                <a href="{{ route('owner.products.edit', $product) }}"
                                    class="btn btn-outline-primary w-100">
                                    <i class="bx bx-edit me-1"></i>Edit Produk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

