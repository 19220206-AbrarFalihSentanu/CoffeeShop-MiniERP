{{-- File: resources/views/admin/products/edit.blade.php --}}

@extends('layouts.app')

@section('title', __('products.edit_product'))

@push('styles')
    <style>
        .image-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-preview:hover {
            border-color: #8B5A2B;
            background-color: #f8f9fa;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-placeholder {
            text-align: center;
            color: #999;
        }

        .discount-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-edit me-2"></i>{{ __('products.edit_product') }}: {{ $product->name }}
                    </h5>
                    <span class="badge bg-label-info">SKU: {{ $product->sku }}</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
                        id="productForm">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Dasar Produk --}}
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-info-circle me-1"></i>{{ __('products.product_details') }}
                                </h6>

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        {{ __('products.product_name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $product->name) }}" required
                                        autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">
                                                {{ __('products.product_category') }} <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id" required>
                                                <option value="">{{ __('categories.all_categories') }}</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">
                                                {{ __('general.type') }} <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('type') is-invalid @enderror" id="type"
                                                name="type" required>
                                                <option value="whole_bean"
                                                    {{ old('type', $product->type) == 'whole_bean' ? 'selected' : '' }}>
                                                    Whole Bean (Biji Utuh)
                                                </option>
                                                <option value="ground"
                                                    {{ old('type', $product->type) == 'ground' ? 'selected' : '' }}>
                                                    Ground (Bubuk)
                                                </option>
                                                <option value="instant"
                                                    {{ old('type', $product->type) == 'instant' ? 'selected' : '' }}>
                                                    Instant
                                                </option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description"
                                        class="form-label">{{ __('products.product_description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">
                                                {{ __('products.weight') }} (gram) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                                id="weight" name="weight" value="{{ old('weight', $product->weight) }}"
                                                step="0.01" min="0" required>
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">
                                                Satuan <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('unit') is-invalid @enderror" id="unit"
                                                name="unit" required>
                                                @foreach (\App\Models\Product::UNITS as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('unit', $product->unit ?? 'kg') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="min_order_qty" class="form-label">
                                                Min. Order
                                            </label>
                                            <input type="number"
                                                class="form-control @error('min_order_qty') is-invalid @enderror"
                                                id="min_order_qty" name="min_order_qty"
                                                value="{{ old('min_order_qty', $product->min_order_qty ?? 0.5) }}"
                                                step="0.001" min="0">
                                            @error('min_order_qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Jumlah minimum per order</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order_increment" class="form-label">
                                                Kelipatan Order
                                            </label>
                                            <input type="number"
                                                class="form-control @error('order_increment') is-invalid @enderror"
                                                id="order_increment" name="order_increment"
                                                value="{{ old('order_increment', $product->order_increment ?? 0.5) }}"
                                                step="0.001" min="0">
                                            @error('order_increment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Kelipatan qty pemesanan (contoh: 0.5 kg)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost_price" class="form-label">
                                                Harga Modal / HPP (Rp) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('cost_price') is-invalid @enderror"
                                                id="cost_price" name="cost_price"
                                                value="{{ old('cost_price', $product->cost_price) }}" step="0.01"
                                                min="0" required>
                                            @error('cost_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Harga pokok / Cost of Goods Sold (COGS)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">
                                                Harga Jual (Rp) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('price') is-invalid @enderror" id="price"
                                                name="price" value="{{ old('price', $product->price) }}"
                                                step="0.01" min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Margin Keuntungan</label>
                                            <div class="alert alert-info py-2 mb-0">
                                                <strong id="margin_percentage">0%</strong> | <strong id="margin_value">Rp
                                                    0</strong>
                                            </div>
                                            <small class="text-muted">Dihitung otomatis</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Gambar --}}
                            <div class="col-md-4">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-image me-1"></i>Gambar Produk
                                </h6>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Ganti Gambar</label>
                                    <div class="image-preview mb-2" id="imagePreview"
                                        onclick="document.getElementById('image').click()">
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="image-preview-placeholder">
                                                <i class="bx bx-image-add bx-lg"></i>
                                                <p class="mb-0 mt-2 small">Klik untuk upload</p>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file"
                                        class="form-control d-none @error('image') is-invalid @enderror" id="image"
                                        name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                        onchange="previewImage(event)">
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload gambar baru untuk mengganti gambar lama</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Pengaturan Diskon --}}
                        <h6 class="text-primary mb-3">
                            <i class="bx bx-purchase-tag me-1"></i>Pengaturan Diskon
                        </h6>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="has_discount" name="has_discount"
                                value="1" {{ old('has_discount', $product->has_discount) ? 'checked' : '' }}
                                onchange="toggleDiscountSection()">
                            <label class="form-check-label" for="has_discount">
                                <strong>Aktifkan Diskon untuk Produk Ini</strong>
                            </label>
                        </div>

                        <div class="discount-section" id="discountSection"
                            style="display: {{ old('has_discount', $product->has_discount) ? 'block' : 'none' }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discount_type" class="form-label">
                                            Tipe Diskon <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('discount_type') is-invalid @enderror"
                                            id="discount_type" name="discount_type" onchange="updateDiscountLabel()">
                                            <option value="">Pilih Tipe</option>
                                            <option value="percentage"
                                                {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>
                                                Persentase (%)
                                            </option>
                                            <option value="fixed"
                                                {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                Nominal Tetap (Rp)
                                            </option>
                                        </select>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discount_value" class="form-label">
                                            <span id="discount_value_label">Nilai Diskon</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('discount_value') is-invalid @enderror"
                                                id="discount_value" name="discount_value"
                                                value="{{ old('discount_value', $product->discount_value) }}"
                                                step="0.01" min="0">
                                            <span class="input-group-text" id="discount_suffix">%</span>
                                        </div>
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted" id="discount_hint">
                                            Contoh: 15 untuk diskon 15%
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Preview Harga Setelah Diskon</label>
                                        <div class="alert alert-info mb-0 py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted">Normal:</small>
                                                    <br>
                                                    <span id="preview_normal_price">Rp 0</span>
                                                </div>
                                                <div>
                                                    <i class="bx bx-right-arrow-alt"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Diskon:</small>
                                                    <br>
                                                    <strong class="text-success" id="preview_discount_price">Rp 0</strong>
                                                </div>
                                            </div>
                                            <small class="text-muted">Hemat: <span id="preview_savings">Rp
                                                    0</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_start_date" class="form-label">
                                            Tanggal Mulai Diskon
                                        </label>
                                        <input type="date"
                                            class="form-control @error('discount_start_date') is-invalid @enderror"
                                            id="discount_start_date" name="discount_start_date"
                                            value="{{ old('discount_start_date', $product->discount_start_date?->format('Y-m-d')) }}">
                                        @error('discount_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_end_date" class="form-label">
                                            Tanggal Berakhir Diskon
                                        </label>
                                        <input type="date"
                                            class="form-control @error('discount_end_date') is-invalid @enderror"
                                            id="discount_end_date" name="discount_end_date"
                                            value="{{ old('discount_end_date', $product->discount_end_date?->format('Y-m-d')) }}">
                                        @error('discount_end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Stok & Status --}}
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-package me-1"></i>Manajemen Stok
                                </h6>

                                <div class="alert alert-info">
                                    <strong>Stok Saat Ini:</strong>
                                    {{ $product->inventory ? $product->inventory->quantity : 0 }} unit
                                    <br>
                                    <strong>Stok Tersedia:</strong>
                                    {{ $product->inventory ? $product->inventory->available : 0 }} unit
                                    <br>
                                    <small class="text-muted">Untuk mengubah stok, gunakan menu Kelola Inventory</small>
                                </div>

                                <div class="mb-3">
                                    <label for="min_stock" class="form-label">
                                        Minimum Stok (Alert) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                        id="min_stock" name="min_stock"
                                        value="{{ old('min_stock', $product->min_stock) }}" min="0" required>
                                    @error('min_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-cog me-1"></i>Status Produk
                                </h6>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <strong>Produk Aktif</strong>
                                            <br>
                                            <small class="text-muted">Produk dapat dilihat dan dibeli oleh customer</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured"
                                            name="is_featured" value="1"
                                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            <strong>Produk Featured</strong>
                                            <br>
                                            <small class="text-muted">Tampilkan di halaman utama</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview gambar saat dipilih
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        }

        // Toggle discount section - FIX: disable field saat tidak aktif
        function toggleDiscountSection() {
            const checkbox = document.getElementById('has_discount');
            const section = document.getElementById('discountSection');
            const discountType = document.getElementById('discount_type');
            const discountValue = document.getElementById('discount_value');
            const startDate = document.getElementById('discount_start_date');
            const endDate = document.getElementById('discount_end_date');

            if (checkbox.checked) {
                section.style.display = 'block';
                // Enable dan set required
                discountType.removeAttribute('disabled');
                discountValue.removeAttribute('disabled');
                startDate.removeAttribute('disabled');
                endDate.removeAttribute('disabled');
                discountType.setAttribute('required', 'required');
                discountValue.setAttribute('required', 'required');
            } else {
                section.style.display = 'none';
                // Disable dan remove required - INI YANG PENTING!
                discountType.setAttribute('disabled', 'disabled');
                discountValue.setAttribute('disabled', 'disabled');
                startDate.setAttribute('disabled', 'disabled');
                endDate.setAttribute('disabled', 'disabled');
                discountType.removeAttribute('required');
                discountValue.removeAttribute('required');
                // Clear values
                discountType.value = '';
                discountValue.value = '';
            }
        }

        // Update discount label based on type
        function updateDiscountLabel() {
            const type = document.getElementById('discount_type').value;
            const suffix = document.getElementById('discount_suffix');
            const label = document.getElementById('discount_value_label');
            const hint = document.getElementById('discount_hint');

            if (type === 'percentage') {
                suffix.textContent = '%';
                label.textContent = 'Persentase Diskon';
                hint.textContent = 'Contoh: 15 untuk diskon 15%';
            } else if (type === 'fixed') {
                suffix.textContent = 'Rp';
                label.textContent = 'Nominal Diskon';
                hint.textContent = 'Contoh: 10000 untuk diskon Rp 10.000';
            }

            calculateDiscountPreview();
        }

        // Calculate discount preview
        function calculateDiscountPreview() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const discountType = document.getElementById('discount_type').value;
            const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;

            let finalPrice = price;
            let savings = 0;

            if (discountType === 'percentage') {
                savings = price * (discountValue / 100);
                finalPrice = price - savings;
            } else if (discountType === 'fixed') {
                savings = discountValue;
                finalPrice = price - savings;
            }

            // Update preview
            document.getElementById('preview_normal_price').textContent = formatRupiah(price);
            document.getElementById('preview_discount_price').textContent = formatRupiah(finalPrice);
            document.getElementById('preview_savings').textContent = formatRupiah(savings);
        }

        // Format angka ke rupiah
        function formatRupiah(amount) {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        // Calculate margin profit
        function calculateMargin() {
            const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
            const sellPrice = parseFloat(document.getElementById('price').value) || 0;

            if (costPrice === 0) {
                document.getElementById('margin_percentage').textContent = '0%';
                document.getElementById('margin_value').textContent = 'Rp 0';
                return;
            }

            const marginValue = sellPrice - costPrice;
            const marginPercentage = (marginValue / costPrice) * 100;

            document.getElementById('margin_percentage').textContent = marginPercentage.toFixed(2) + '%';
            document.getElementById('margin_value').textContent = formatRupiah(marginValue);
        }

        // Event listeners
        document.getElementById('cost_price').addEventListener('input', () => {
            calculateMargin();
            calculateDiscountPreview();
        });
        document.getElementById('price').addEventListener('input', () => {
            calculateMargin();
            calculateDiscountPreview();
        });
        document.getElementById('discount_type').addEventListener('change', calculateDiscountPreview);
        document.getElementById('discount_value').addEventListener('input', calculateDiscountPreview);

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDiscountSection(); // Set initial state - PENTING!
            calculateMargin();
            calculateDiscountPreview();
        });
    </script>
@endpush


