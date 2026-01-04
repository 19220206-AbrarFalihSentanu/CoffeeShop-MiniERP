{{-- File: resources/views/cart/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
        }

        .quantity-control {
            display: inline-flex;
            align-items: center;
            gap: 0;
            width: auto;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .quantity-control input[type="number"] {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
            height: 38px;
            padding: 0.5rem 0.25rem !important;
            font-size: 0.85rem !important;
            font-weight: 500 !important;
            text-align: center !important;
            border: none !important;
            background-color: #f8f9fa;
            appearance: textfield;
            margin: 0;
        }

        .quantity-control input[type="number"]::-webkit-outer-spin-button,
        .quantity-control input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .quantity-control .btn-qty {
            padding: 0.375rem 0.75rem;
            height: 38px;
            min-width: 38px;
            width: auto;
            border: none;
            background-color: #f8f9fa;
            color: #495057;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .quantity-control .btn-qty:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .quantity-control .btn-qty:active {
            background-color: #dee2e6;
        }

        .quantity-control .btn-qty:first-child {
            border-right: 1px solid #ced4da;
        }

        .quantity-control .btn-qty:last-child {
            border-left: 1px solid #ced4da;
        }

        .cart-summary {
            position: sticky;
            top: 20px;
        }

        .product-image-cart {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-cart me-2"></i>Keranjang Belanja</h4>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
            <i class="bx bx-left-arrow-alt me-1"></i>Lanjut Belanja
        </a>
    </div>

    @if ($cartItems->isEmpty())
        {{-- Empty Cart --}}
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-cart-alt bx-lg text-muted mb-3"></i>
                <h4>Keranjang Belanja Kosong</h4>
                <p class="text-muted mb-4">Anda belum menambahkan produk ke keranjang.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    <i class="bx bx-shopping-bag me-1"></i>Mulai Belanja
                </a>
            </div>
        </div>
    @else
        {{-- Alerts for Issues --}}
        @if ($unavailableItems->count() > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h6 class="alert-heading mb-2">
                    <i class="bx bx-error-circle me-1"></i>Beberapa Produk Tidak Tersedia
                </h6>
                <p class="mb-2">Produk berikut tidak tersedia atau stok habis:</p>
                <ul class="mb-2">
                    @foreach ($unavailableItems as $item)
                        <li>{{ $item->product->name }} - <strong>Stok Habis</strong></li>
                    @endforeach
                </ul>
                <small>Silakan hapus produk tersebut untuk melanjutkan checkout.</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($priceChangedItems->count() > 0)
            <div class="alert alert-warning alert-dismissible" role="alert">
                <h6 class="alert-heading mb-2">
                    <i class="bx bx-info-circle me-1"></i>Perubahan Harga
                </h6>
                <p class="mb-2">Harga beberapa produk telah berubah sejak ditambahkan ke keranjang:</p>
                <ul class="mb-2">
                    @foreach ($priceChangedItems as $item)
                        <li>
                            {{ $item->product->name }} -
                            <del>Rp {{ number_format($item->price, 0, ',', '.') }}</del>
                            <strong class="text-success">Rp
                                {{ number_format($item->getCurrentProductPrice(), 0, ',', '.') }}</strong>
                        </li>
                    @endforeach
                </ul>
                <form action="{{ route('customer.updatePrices') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bx bx-refresh me-1"></i>Update Semua Harga
                    </button>
                </form>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Cart Items --}}
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Produk di Keranjang ({{ $cartItems->count() }} item)</h5>
                        <form action="{{ route('customer.clear') }}" method="POST"
                            onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash me-1"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th style="width: 150px">Jumlah</th>
                                        <th>Subtotal</th>
                                        <th style="width: 80px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr class="cart-item {{ !$item->isAvailable() ? 'table-danger' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product->image)
                                                        <img src="{{ Storage::url($item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="product-image-cart me-3">
                                                    @else
                                                        <div
                                                            class="bg-light d-flex align-items-center justify-content-center product-image-cart me-3">
                                                            <i class="bx bx-coffee text-muted"></i>
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('catalog.show', $item->product->slug) }}"
                                                                class="text-dark">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h6>
                                                        <small class="text-muted">
                                                            <span
                                                                class="badge bg-label-info">{{ $item->product->category->name }}</span>
                                                            | {{ $item->product->unit ?? 'kg' }}
                                                        </small>
                                                        <br>
                                                        @if (!$item->isAvailable())
                                                            <span class="badge bg-danger mt-1">
                                                                <i class="bx bx-x-circle"></i> Stok Habis
                                                            </span>
                                                        @elseif($item->product->getAvailableStock() < $item->quantity)
                                                            <span class="badge bg-warning mt-1">
                                                                <i class="bx bx-error"></i> Stok terbatas:
                                                                {{ $item->product->formatQuantity($item->product->getAvailableStock()) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($item->hasPriceChanged())
                                                    <del class="text-muted small">Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</del>
                                                    <br>
                                                    <strong class="text-success">Rp
                                                        {{ number_format($item->getCurrentProductPrice(), 0, ',', '.') }}</strong>
                                                @else
                                                    <strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                                @endif
                                                <small
                                                    class="text-muted d-block">/{{ $item->product->unit ?? 'kg' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $increment =
                                                        $item->product->order_increment > 0
                                                            ? $item->product->order_increment
                                                            : 0.5;
                                                    $minQty =
                                                        $item->product->min_order_qty > 0
                                                            ? $item->product->min_order_qty
                                                            : $increment;
                                                    $maxQty = $item->product->getAvailableStock();
                                                @endphp
                                                <form action="{{ route('customer.update', $item) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="quantity-control">
                                                        <button class="btn-qty" type="button"
                                                            onclick="decreaseQuantity({{ $item->id }}, {{ $increment }}, {{ $minQty }})">
                                                            <i class="bx bx-minus"></i>
                                                        </button>
                                                        <input type="number" id="qty-{{ $item->id }}" name="quantity"
                                                            value="{{ rtrim(rtrim(number_format($item->quantity, 3, '.', ''), '0'), '.') }}"
                                                            min="{{ $minQty }}" max="{{ $maxQty }}"
                                                            step="{{ $increment }}" onchange="this.form.submit()">
                                                        <button class="btn-qty" type="button"
                                                            onclick="increaseQuantity({{ $item->id }}, {{ $maxQty }}, {{ $increment }}, '{{ $item->product->unit ?? 'kg' }}')">
                                                            <i class="bx bx-plus"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                                <small
                                                    class="text-muted d-block mt-1">{{ $item->product->unit ?? 'kg' }}</small>
                                            </td>
                                            <td>
                                                <strong class="text-primary">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td>
                                                <form action="{{ route('customer.remove', $item) }}" method="POST"
                                                    onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Hapus">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="cart-summary">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ number_format($cartItems->sum('quantity'), 2) }} total)</span>
                                <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>

                            {{-- TAX - TAMPILKAN JIKA ADA --}}
                            @if ($tax > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Pajak PPN ({{ number_format($taxRate, 1) }}%)</span>
                                    <strong>Rp {{ number_format($tax, 0, ',', '.') }}</strong>
                                </div>
                            @endif

                            @if ($shipping > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkir</span>
                                    <strong>Rp {{ number_format($shipping, 0, ',', '.') }}</strong>
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total</h5>
                                <h4 class="text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                            </div>

                            @if ($unavailableItems->count() > 0)
                                <button class="btn btn-danger w-100 disabled" disabled>
                                    <i class="bx bx-x-circle me-1"></i>Ada Produk Tidak Tersedia
                                </button>
                            @else
                                <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary w-100 mb-2">
                                    <i class="bx bx-credit-card me-1"></i>Lanjut ke Checkout
                                </a>
                            @endif

                            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bx bx-left-arrow-alt me-1"></i>Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    {{-- Promo Code (Future) --}}
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">Kode Promo</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan kode promo">
                                <button class="btn btn-outline-primary" type="button">Apply</button>
                            </div>
                            <small class="text-muted">Fitur promo akan segera tersedia</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function decreaseQuantity(itemId, increment, minQty) {
            const input = document.getElementById('qty-' + itemId);
            let value = parseFloat(input.value);
            let newValue = value - increment;
            if (newValue >= minQty) {
                input.value = newValue.toFixed(3).replace(/\.?0+$/, '');
                input.form.submit();
            } else {
                alert('Jumlah minimal: ' + minQty);
            }
        }

        function increaseQuantity(itemId, maxQty, increment, unit) {
            const input = document.getElementById('qty-' + itemId);
            let value = parseFloat(input.value);
            let newValue = value + increment;
            if (newValue <= maxQty) {
                input.value = newValue.toFixed(3).replace(/\.?0+$/, '');
                input.form.submit();
            } else {
                alert('Stok maksimal: ' + maxQty + ' ' + unit);
            }
        }
    </script>
@endpush
