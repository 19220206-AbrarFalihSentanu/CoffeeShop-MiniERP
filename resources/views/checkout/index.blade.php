{{-- File: resources/views/checkout/index.blade.php --}}

@extends('layouts.app')

@section('title', __('cart.checkout'))

@push('styles')
    <style>
        .checkout-section {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .order-summary-card {
            position: sticky;
            top: 20px;
        }

        .product-mini-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">{{ __('cart.catalog') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">{{ __('cart.cart') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('cart.checkout') }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-credit-card me-2"></i>{{ __('cart.checkout') }}</h4>
        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-left-arrow-alt me-1"></i>{{ __('general.back') }} {{ __('cart.cart') }}
        </a>
    </div>

    <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            {{-- Left Column: Forms --}}
            <div class="col-lg-7 mb-4">
                {{-- Informasi Pengiriman --}}
                <div class="checkout-section">
                    <h5 class="mb-3">
                        <i class="bx bx-map-pin me-2 text-primary"></i>{{ __('cart.shipping_info') }}
                    </h5>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label">{{ __('orders.customer_name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                            id="customer_name" name="customer_name"
                            value="{{ old('customer_name', auth()->user()->name) }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">{{ __('general.email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                id="customer_email" name="customer_email"
                                value="{{ old('customer_email', auth()->user()->email) }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_phone" class="form-label">{{ __('general.phone') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                id="customer_phone" name="customer_phone"
                                value="{{ old('customer_phone', auth()->user()->phone) }}" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">{{ __('orders.shipping_address') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address"
                            name="shipping_address" rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">{{ __('cart.address_helper') }}</small>
                    </div>

                    <div class="mb-0">
                        <label for="customer_notes" class="form-label">{{ __('general.notes') }}
                            ({{ __('general.optional') }})</label>
                        <textarea class="form-control @error('customer_notes') is-invalid @enderror" id="customer_notes" name="customer_notes"
                            rows="2" placeholder="{{ __('cart.notes_placeholder') }}">{{ old('customer_notes') }}</textarea>
                        @error('customer_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror>
                    </div>
                </div>

                {{-- Review Items --}}
                <div class="checkout-section">
                    <h5 class="mb-3">
                        <i class="bx bx-package me-2 text-primary"></i>{{ __('cart.order_review') }}
                        ({{ $cartItems->count() }} item)
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('products.product') }}</th>
                                    <th class="text-end">{{ __('general.quantity') }}</th>
                                    <th class="text-end">{{ __('general.price') }}</th>
                                    <th class="text-end">{{ __('general.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                        alt="{{ $item->product->name }}" class="product-mini-image me-3">
                                                @else
                                                    <div
                                                        class="bg-light d-flex align-items-center justify-content-center product-mini-image me-3">
                                                        <i class="bx bx-coffee text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->product->name }}</div>
                                                    <small class="text-muted">{{ $item->product->weight }}g</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">{{ $item->quantity }} x</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Column: Order Summary --}}
            <div class="col-lg-5">
                <div class="order-summary-card">
                    <div class="checkout-section">
                        <h5 class="mb-3">
                            <i class="bx bx-receipt me-2 text-primary"></i>{{ __('cart.order_summary') }}
                        </h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('cart.subtotal') }} ({{ $cartItems->sum('quantity') }} item)</span>
                            <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>

                        @if ($tax > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    {{ __('cart.tax') }} PPN ({{ number_format($taxRate, 1) }}%)
                                    <i class="bx bx-info-circle text-muted" data-bs-toggle="tooltip"
                                        title="{{ __('cart.tax_tooltip') }}"></i>
                                </span>
                                <strong>Rp {{ number_format($tax, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        @if ($shipping > 0)
                            <div class="d-flex justify-content-between mb-3">
                                <span>{{ __('cart.shipping_cost') }}</span>
                                <strong>Rp {{ number_format($shipping, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <h5>{{ __('cart.grand_total') }}</h5>
                            <h4 class="text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>

                        {{-- Info Box --}}
                        <div class="alert alert-info py-2 mb-3">
                            <small>
                                <i class="bx bx-info-circle me-1"></i>
                                <strong>{{ __('general.notes') }}:</strong> {{ __('cart.order_approval_note') }}
                            </small>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-primary w-100 btn-lg" id="submitBtn">
                            <i class="bx bx-check-circle me-1"></i>{{ __('cart.place_order') }}
                        </button>

                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bx bx-left-arrow-alt me-1"></i>{{ __('general.back') }} {{ __('cart.cart') }}
                        </a>

                        {{-- Trust Indicators --}}
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-check-shield text-success me-2"></i>
                                <small>{{ __('cart.trust_secure') }}</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-package text-primary me-2"></i>
                                <small>{{ __('cart.trust_packaging') }}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-phone text-info me-2"></i>
                                <small>{{ __('cart.trust_support') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>{{ __('general.processing') }}';
        });

        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
@endpush
