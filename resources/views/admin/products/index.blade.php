{{-- File: resources/views/admin/products/index.blade.php --}}

@extends('layouts.app')

@section('title', __('products.manage_products'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('products.product_list') }}</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> {{ __('products.add_product') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Filter & Search --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">{{ __('products.all_categories') }}</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">{{ __('general.all') }} {{ __('general.type') }}</option>
                            <option value="whole_bean" {{ request('type') == 'whole_bean' ? 'selected' : '' }}>Whole Bean
                            </option>
                            <option value="ground" {{ request('type') == 'ground' ? 'selected' : '' }}>Ground</option>
                            <option value="instant" {{ request('type') == 'instant' ? 'selected' : '' }}>Instant</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="stock_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">{{ __('general.all') }} {{ __('inventory.stock') }}</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                {{ __('products.low_stock') }}
                            </option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>
                                {{ __('products.out_of_stock') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="{{ __('products.search_products') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bx bx-search"></i> {{ __('general.search') }}
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px">{{ __('general.image') }}</th>
                            <th>SKU</th>
                            <th>{{ __('products.product_name') }}</th>
                            <th>{{ __('general.category') }}</th>
                            <th>{{ __('general.type') }}</th>
                            <th>{{ __('products.cost_price') }}</th>
                            <th>{{ __('products.selling_price') }}</th>
                            <th>Margin</th>
                            <th>{{ __('general.discount') }}</th>
                            <th>{{ __('inventory.stock') }}</th>
                            <th>{{ __('general.status') }}</th>
                            <th style="width: 100px">{{ __('general.actions') }}</th>
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
                                    <code>{{ $product->sku }}</code>
                                    @if ($product->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->weight }}g</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    @if ($product->type === 'whole_bean')
                                        <i class="bx bx-coffee-bean"></i> Whole Bean
                                    @elseif($product->type === 'ground')
                                        <i class="bx bx-coffee"></i> Ground
                                    @else
                                        <i class="bx bx-coffee-togo"></i> Instant
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">Rp
                                        {{ number_format($product->cost_price, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    @if ($product->isDiscountActive())
                                        <div>
                                            <del class="text-muted small">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</del>
                                            <br>
                                            <strong class="text-success">Rp
                                                {{ number_format($product->final_price, 0, ',', '.') }}</strong>
                                        </div>
                                    @else
                                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $margin =
                                            (($product->price - $product->cost_price) / $product->cost_price) * 100;
                                    @endphp
                                    <small
                                        class="{{ $margin > 50 ? 'text-success' : ($margin > 30 ? 'text-warning' : 'text-danger') }}">
                                        {{ number_format($margin, 1) }}%
                                    </small>
                                    <br>
                                    <small class="text-muted">Rp
                                        {{ number_format($product->price - $product->cost_price, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    @if ($product->isDiscountActive())
                                        <span class="badge bg-danger">
                                            -{{ number_format($product->discount_percentage, 0) }}%
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            s/d
                                            {{ $product->discount_end_date ? $product->discount_end_date->format('d/m/Y') : '-' }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->inventory)
                                        @if ($product->inventory->available <= 0)
                                            <span class="badge bg-danger">{{ __('products.out_of_stock') }}</span>
                                        @elseif($product->isLowStock())
                                            <span class="badge bg-warning">
                                                {{ $product->inventory->available }}
                                            </span>
                                            <br>
                                            <small class="text-danger">{{ __('products.low_stock') }}!</small>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $product->inventory->available }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.products.toggleStatus', $product) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="badge border-0 {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->is_active ? __('general.active') : __('general.inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.products.show', $product) }}">
                                                <i class="bx bx-show me-1"></i> {{ __('general.view') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                                <i class="bx bx-edit me-1"></i> {{ __('general.edit') }}
                                            </a>
                                            <form action="{{ route('admin.products.toggleFeatured', $product) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-star me-1"></i>
                                                    {{ $product->is_featured ? __('products.archive_product') : __('products.is_featured') }}
                                                </button>
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                data-confirm="{{ __('products.confirm_delete_product') }}"
                                                data-confirm-title="Hapus Produk?" data-confirm-icon="warning"
                                                data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-1"></i> {{ __('general.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bx bx-package bx-lg text-muted"></i>
                                    <p class="text-muted mt-2">{{ __('products.no_products') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
