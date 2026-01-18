{{-- File: resources/views/catalog/index.blade.php --}}

@extends('layouts.app')

@section('title', __('cart.product_catalog'))

@push('styles')
    <style>
        .product-card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
        }

        .price-final {
            font-size: 1.5rem;
            font-weight: bold;
            color: #8B5A2B;
        }

        .price-discount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }

        .filter-sidebar {
            position: sticky;
            top: 20px;
        }

        .category-item {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-item:hover {
            background-color: #f8f9fa;
            padding-left: 1rem;
        }

        .category-item.active {
            background-color: #8B5A2B;
            color: white;
            padding-left: 1rem;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <div class="card bg-primary text-white mb-4">
        <div class="card-body py-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white mb-2">
                        <i class="bx bx-coffee me-2"></i>{{ __('cart.product_catalog') }} Eureka Coffee
                    </h2>
                    <p class="mb-0">{{ __('cart.browse_products') }}</p>
                </div>
                <div class="col-md-4 text-end">
                    @if (Auth::user()->isCustomer())
                        <a href="{{ route('customer.index') }}" class="btn btn-light">
                            <i class="bx bx-cart me-1"></i>{{ __('cart.shopping_cart') }}
                            @if (auth()->user()->cart_count > 0)
                                <span class="badge bg-danger">{{ auth()->user()->cart_count }}</span>
                            @endif
                        </a>
                    @else
                        <span class="badge bg-light text-primary">
                            Anda login sebagai {{ Auth::user()->role->display_name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar Filter --}}
        <div class="col-md-3">
            <div class="filter-sidebar">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bx bx-filter me-1"></i>{{ __('general.filter') }} {{ __('products.product') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="filterForm">
                            {{-- Search --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('cart.search_products') }}</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="{{ __('products.product_name') }}..." value="{{ request('search') }}"
                                    onchange="this.form.submit()">
                            </div>

                            {{-- Categories --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('general.category') }}</label>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('catalog.index') }}"
                                        class="list-group-item list-group-item-action category-item {{ !request('category') ? 'active' : '' }}">
                                        {{ __('products.all_categories') }}
                                        <span class="badge bg-primary float-end">{{ $products->total() }}</span>
                                    </a>
                                    @foreach ($categories as $cat)
                                        <a href="{{ route('catalog.index', ['category' => $cat->id] + request()->except('category')) }}"
                                            class="list-group-item list-group-item-action category-item {{ request('category') == $cat->id ? 'active' : '' }}">
                                            {{ $cat->name }}
                                            <span
                                                class="badge {{ request('category') == $cat->id ? 'bg-white text-primary' : 'bg-primary' }} float-end">
                                                {{ $cat->products_count }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Product Type --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('general.type') }}
                                    {{ __('products.product') }}</label>
                                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">{{ __('general.all') }} {{ __('general.type') }}</option>
                                    <option value="whole_bean" {{ request('type') == 'whole_bean' ? 'selected' : '' }}>
                                        Whole Bean
                                    </option>
                                    <option value="ground" {{ request('type') == 'ground' ? 'selected' : '' }}>Ground
                                    </option>
                                    <option value="instant" {{ request('type') == 'instant' ? 'selected' : '' }}>Instant
                                    </option>
                                </select>
                            </div>

                            {{-- Price Range --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('cart.price_range') }}</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" class="form-control form-control-sm"
                                            placeholder="Min" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" class="form-control form-control-sm"
                                            placeholder="Max" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Special Filters --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">{{ __('general.filter') }}</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sale" value="1"
                                        id="filterSale" {{ request('sale') == '1' ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <label class="form-check-label small" for="filterSale">
                                        <i class="bx bx-purchase-tag text-danger"></i> {{ __('general.discount') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="featured" value="1"
                                        id="filterFeatured" {{ request('featured') == '1' ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <label class="form-check-label small" for="filterFeatured">
                                        <i class="bx bx-star text-warning"></i> {{ __('products.is_featured') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bx bx-search me-1"></i>{{ __('cart.apply_filter') }}
                            </button>
                            <a href="{{ route('catalog.index') }}" class="btn btn-secondary btn-sm w-100 mt-2">
                                <i class="bx bx-reset me-1"></i>{{ __('cart.clear_filter') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="col-md-9">
            {{-- Toolbar --}}
            <div class="card mb-3">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0">
                                {{ __('general.showing') }} <strong>{{ $products->count() }}</strong>
                                {{ __('general.of') }}
                                <strong>{{ $products->total() }}</strong> {{ __('products.products') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="row g-2 align-items-center">
                                @foreach (request()->except(['sort', 'per_page']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <div class="col-auto">
                                    <label class="col-form-label col-form-label-sm">{{ __('cart.sort_by') }}:</label>
                                </div>
                                <div class="col">
                                    <select name="sort" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                            {{ __('cart.sort_newest') }}
                                        </option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                            {{ __('cart.sort_price_low') }}
                                        </option>
                                        <option value="price_high"
                                            {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                            {{ __('cart.sort_price_high') }}
                                        </option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                            {{ __('cart.sort_name_asc') }}
                                        </option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                            {{ __('cart.sort_name_desc') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <select name="per_page" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12
                                        </option>
                                        <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24
                                        </option>
                                        <option value="36" {{ request('per_page') == '36' ? 'selected' : '' }}>36
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="row">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card product-card">
                            <div class="position-relative">
                                {{-- Featured Badge --}}
                                @if ($product->is_featured)
                                    <span class="product-badge badge bg-warning">
                                        <i class="bx bx-star"></i> Featured
                                    </span>
                                @endif

                                {{-- Discount Badge --}}
                                @if ($product->isDiscountActive())
                                    <span class="discount-badge badge bg-danger">
                                        -{{ number_format($product->discount_percentage, 0) }}%
                                    </span>
                                @endif

                                {{-- Product Image --}}
                                <a href="{{ route('catalog.show', $product->slug) }}">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="product-image">
                                    @else
                                        <div
                                            class="bg-light d-flex align-items-center justify-content-center product-image">
                                            <i class="bx bx-coffee bx-lg text-muted"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="card-body">
                                {{-- Category --}}
                                <p class="mb-2">
                                    <span class="badge bg-label-info">{{ $product->category->name }}</span>
                                    <span class="badge bg-label-secondary">
                                        @if ($product->type === 'whole_bean')
                                            <i class="bx bx-coffee-bean"></i> Whole Bean
                                        @elseif($product->type === 'ground')
                                            <i class="bx bx-coffee"></i> Ground
                                        @else
                                            <i class="bx bx-coffee-togo"></i> Instant
                                        @endif
                                    </span>
                                </p>

                                {{-- Product Name --}}
                                <h6 class="mb-2">
                                    <a href="{{ route('catalog.show', $product->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                {{-- Weight --}}
                                <p class="text-muted small mb-2">
                                    <i class="bx bx-package"></i> {{ $product->weight }}g
                                </p>

                                {{-- Price --}}
                                <div class="mb-3">
                                    @if ($product->isDiscountActive())
                                        <div class="price-original">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                        <div class="price-discount">
                                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                        </div>
                                        <small class="text-danger">
                                            Hemat Rp {{ number_format($product->savings_amount, 0, ',', '.') }}
                                        </small>
                                    @else
                                        <div class="price-final">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Stock Info --}}
                                <p class="mb-3">
                                    @if ($product->getAvailableStock() > 0)
                                        <span class="badge bg-success">
                                            <i class="bx bx-check-circle"></i> {{ __('cart.stock') }}:
                                            {{ $product->getAvailableStock() }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bx bx-x-circle"></i> {{ __('cart.out_of_stock') }}
                                        </span>
                                    @endif
                                </p>

                                {{-- Action Buttons --}}
                                <div class="d-grid gap-2">
                                    @if (Auth::user()->isCustomer())
                                        @if ($product->getAvailableStock() > 0)
                                            <form action="{{ route('customer.add', $product) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                                    <i class="bx bx-cart-add me-1"></i>{{ __('cart.add_to_cart') }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="bx bx-x-circle me-1"></i>{{ __('cart.out_of_stock') }}
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('catalog.show', $product->slug) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bx bx-show me-1"></i>{{ __('general.view') }}
                                            {{ __('general.detail') }}
                                        </a>
                                    @endif

                                    <a href="{{ route('catalog.show', $product->slug) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="bx bx-info-circle me-1"></i>{{ __('products.product_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bx bx-search-alt bx-lg text-muted mb-3"></i>
                                <h5>{{ __('products.product_not_found') }}</h5>
                                <p class="text-muted">{{ __('cart.no_results') }}</p>
                                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                                    <i class="bx bx-reset me-1"></i>{{ __('cart.clear_filter') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <x-pagination-with-info :paginator="$products" />
        </div>
    </div>
@endsection

@push('scripts')
    {{-- No custom scripts needed, forms will submit directly --}}
@endpush


