{{-- File: resources/views/catalog/show.blade.php --}}

@extends('layouts.app')

@section('title', $product->name)

@push('styles')
    <style>
        .product-main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
        }

        .quantity-input {
            width: 100px;
        }

        .related-product-card {
            transition: all 0.3s ease;
        }

        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .related-product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">{{ __('cart.catalog') }}</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('catalog.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Product Details --}}
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        {{-- Product Image --}}
                        <div class="col-md-5">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="product-main-image">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center product-main-image">
                                    <i class="bx bx-coffee bx-lg text-muted"></i>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="mt-3">
                                @if ($product->is_featured)
                                    <span class="badge bg-warning me-2">
                                        <i class="bx bx-star"></i> {{ __('products.is_featured') }}
                                    </span>
                                @endif
                                @if ($product->isDiscountActive())
                                    <span class="badge bg-danger me-2">
                                        <i class="bx bx-purchase-tag"></i> {{ __('general.discount') }}
                                        {{ number_format($product->discount_percentage, 0) }}%
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="col-md-7">
                            <h2 class="mb-3">{{ $product->name }}</h2>

                            {{-- Category & Type --}}
                            <div class="mb-3">
                                <span class="badge bg-info me-2">{{ $product->category->name }}</span>
                                <span class="badge bg-secondary">
                                    @if ($product->type === 'whole_bean')
                                        <i class="bx bx-coffee-bean"></i> Whole Bean
                                    @elseif($product->type === 'ground')
                                        <i class="bx bx-coffee"></i> Ground
                                    @else
                                        <i class="bx bx-coffee-togo"></i> Instant
                                    @endif
                                </span>
                            </div>

                            {{-- SKU & Weight --}}
                            <p class="text-muted mb-3">
                                <strong>{{ __('products.product_sku') }}:</strong> <code>{{ $product->sku }}</code> |
                                <strong>{{ __('products.product_weight') }}:</strong> {{ $product->weight }}g |
                                <strong>{{ __('products.product_unit') }}:</strong> {{ $product->unit_label }}
                            </p>

                            {{-- Price --}}
                            <div class="mb-4">
                                @if ($product->isDiscountActive())
                                    <p class="text-muted mb-1">
                                        <del>Rp
                                            {{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}</del>
                                    </p>
                                    <h3 class="text-success mb-2">
                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}<small
                                            class="text-muted fs-6">/{{ $product->unit }}</small>
                                    </h3>
                                    <div class="alert alert-danger py-2">
                                        <i class="bx bx-purchase-tag me-1"></i>
                                        <strong>{{ __('general.discount') }} Rp
                                            {{ number_format($product->savings_amount, 0, ',', '.') }}</strong>
                                        ({{ number_format($product->discount_percentage, 0) }}% OFF)
                                        @if ($product->discount_end_date)
                                            <br>
                                            <small>{{ __('general.date') }}:
                                                {{ $product->discount_end_date->format('d M Y') }}</small>
                                        @endif
                                    </div>
                                @else
                                    <h3 class="text-primary mb-2">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}<small
                                            class="text-muted fs-6">/{{ $product->unit }}</small>
                                    </h3>
                                @endif
                                <small class="text-muted">
                                    <i class="bx bx-info-circle"></i> Min. order:
                                    {{ number_format($product->min_order_qty, 2) }} {{ $product->unit }}
                                    @if ($product->order_increment > 0)
                                        | Kelipatan: {{ number_format($product->order_increment, 2) }}
                                        {{ $product->unit }}
                                    @endif
                                </small>
                            </div>

                            {{-- Stock Info --}}
                            <div class="mb-4">
                                <h6>{{ __('cart.stock') }}:</h6>
                                @if ($product->getAvailableStock() > 0)
                                    <div class="alert alert-success py-2">
                                        <i class="bx bx-check-circle me-1"></i>
                                        <strong>{{ __('cart.available') }}:
                                            {{ number_format($product->getAvailableStock(), 2) }}
                                            {{ $product->unit }}</strong>
                                    </div>
                                @else
                                    <div class="alert alert-danger py-2">
                                        <i class="bx bx-x-circle me-1"></i>
                                        <strong>{{ __('cart.out_of_stock') }}</strong> -
                                        {{ __('cart.item_not_available') }}
                                    </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            @if ($product->description)
                                <div class="mb-4">
                                    <h6>{{ __('cart.description') }}:</h6>
                                    <p class="text-muted">{{ $product->description }}</p>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            @if (Auth::user()->isCustomer())
                                @if ($product->getAvailableStock() > 0)
                                    <form action="{{ route('customer.add', $product) }}" method="POST" id="addToCartForm">
                                        @csrf
                                        <div class="mb-4">
                                            <h6>{{ __('cart.quantity') }} ({{ $product->unit }}):</h6>
                                            <div class="input-group quantity-input mb-3">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="decreaseQty()">
                                                    <i class="bx bx-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" id="quantity"
                                                    name="quantity" value="{{ $product->min_order_qty }}"
                                                    min="{{ $product->min_order_qty }}"
                                                    max="{{ $product->getAvailableStock() }}"
                                                    step="{{ $product->order_increment > 0 ? $product->order_increment : 0.001 }}">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="increaseQty()">
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                Min: {{ number_format($product->min_order_qty, 2) }} {{ $product->unit }}
                                                @if ($product->order_increment > 0)
                                                    | Kelipatan: {{ number_format($product->order_increment, 2) }}
                                                    {{ $product->unit }}
                                                @endif
                                            </small>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bx bx-cart-add me-2"></i>{{ __('cart.add_to_cart') }}
                                            </button>
                                            <a href="{{ route('customer.index') }}"
                                                class="btn btn-outline-secondary btn-lg">
                                                <i class="bx bx-cart me-2"></i>{{ __('cart.shopping_cart') }}
                                            </a>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="bx bx-info-circle me-1"></i>
                                        {{ __('cart.out_of_stock') }}.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Anda login sebagai <strong>{{ Auth::user()->role->display_name }}</strong>. Fitur
                                    pembelian hanya tersedia untuk Customer.
                                </div>
                                <a href="{{ route('catalog.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }} {{ __('cart.catalog') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if ($relatedProducts->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bx bx-package me-2"></i>{{ __('cart.related_products') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($relatedProducts as $related)
                        <div class="col-md-3 mb-3">
                            <div class="card related-product-card h-100">
                                <a href="{{ route('catalog.show', $related->slug) }}">
                                    @if ($related->image)
                                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}"
                                            class="related-product-image">
                                    @else
                                        <div
                                            class="bg-light d-flex align-items-center justify-content-center related-product-image">
                                            <i class="bx bx-coffee bx-lg text-muted"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="card-body">
                                    <h6 class="mb-2">
                                        <a href="{{ route('catalog.show', $related->slug) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $related->name }}
                                        </a>
                                    </h6>

                                    <p class="text-muted small mb-2">{{ $related->weight }}g</p>

                                    @if ($related->isDiscountActive())
                                        <del class="text-muted small">Rp
                                            {{ number_format($related->price, 0, ',', '.') }}</del>
                                        <h6 class="text-success mb-2">
                                            Rp {{ number_format($related->final_price, 0, ',', '.') }}
                                        </h6>
                                    @else
                                        <h6 class="text-primary mb-2">
                                            Rp {{ number_format($related->price, 0, ',', '.') }}
                                        </h6>
                                    @endif

                                    <a href="{{ route('catalog.show', $related->slug) }}"
                                        class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bx bx-show me-1"></i>{{ __('general.view') }}
                                        {{ __('general.detail') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const maxQty = {{ $product->getAvailableStock() }};
        const minQty = {{ $product->min_order_qty }};
        const increment = {{ $product->order_increment > 0 ? $product->order_increment : 0.5 }};
        const unit = '{{ $product->unit }}';

        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            let qty = parseFloat(qtyInput.value) || minQty;
            let newQty = qty + increment;

            if (newQty <= maxQty) {
                qtyInput.value = newQty.toFixed(3).replace(/\.?0+$/, '');
            } else {
                alert(`Stok maksimal: ${maxQty} ${unit}`);
            }
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            let qty = parseFloat(qtyInput.value) || minQty;
            let newQty = qty - increment;

            if (newQty >= minQty) {
                qtyInput.value = newQty.toFixed(3).replace(/\.?0+$/, '');
            } else {
                alert(`Minimum order: ${minQty} ${unit}`);
            }
        }
    </script>
@endpush
