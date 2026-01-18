{{-- File: resources/views/admin/purchase-orders/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@push('styles')
    <style>
        .item-row {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }

        .remove-item {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Buat Purchase Order Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.purchase-orders.store') }}" method="POST" id="poForm">
                        @csrf

                        <div class="row">
                            {{-- Supplier --}}
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">Supplier <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                                    name="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} - {{ $supplier->type_display }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Expected Delivery Date --}}
                            <div class="col-md-6 mb-3">
                                <label for="expected_delivery_date" class="form-label">Tanggal Pengiriman Diharapkan <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('expected_delivery_date') is-invalid @enderror"
                                    id="expected_delivery_date" name="expected_delivery_date"
                                    value="{{ old('expected_delivery_date') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('expected_delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Catatan untuk Owner</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Items Section --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Item Produk</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">
                                <i class='bx bx-plus'></i> Tambah Item
                            </button>
                        </div>

                        <div id="itemsContainer">
                            {{-- Items will be added here dynamically --}}
                        </div>

                        <div class="alert alert-info mt-3" id="noItemsAlert">
                            <i class='bx bx-info-circle'></i> Belum ada item. Klik "Tambah Item" untuk menambahkan produk.
                        </div>

                        {{-- Summary --}}
                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <strong id="subtotalDisplay">Rp 0</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Pajak ({{ setting('tax_rate', 11) }}%):</span>
                                            <strong id="taxDisplay">Rp 0</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">TOTAL:</h5>
                                            <h5 class="mb-0 text-primary" id="totalDisplay">Rp 0</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Simpan sebagai Draft
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
        let itemIndex = 0;
        const taxRate = {{ setting('tax_rate', 11) }};
        const products = @json($products);

        // Template untuk item row
        function getItemTemplate(index) {
            return `
        <div class="item-row" data-index="${index}">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Produk <span class="text-danger">*</span></label>
                    <select class="form-select product-select" name="items[${index}][product_id]" required>
                        <option value="">Pilih Produk</option>
                        ${products.map(p => `
                                <option value="${p.id}" data-price="${p.cost_price || p.price}">
                                    ${p.name} - ${p.category.name}
                                </option>
                            `).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity (kg) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control quantity-input" 
                           name="items[${index}][quantity]" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga/kg <span class="text-danger">*</span></label>
                    <input type="number" class="form-control price-input" 
                           name="items[${index}][unit_price]" min="0" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100 remove-item">
                        <i class='bx bx-trash'></i> Hapus
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 text-end">
                    <small class="text-muted">Subtotal: <strong class="item-subtotal">Rp 0</strong></small>
                </div>
            </div>
        </div>
    `;
        }

        // Add item
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const container = document.getElementById('itemsContainer');
            container.insertAdjacentHTML('beforeend', getItemTemplate(itemIndex));
            itemIndex++;
            updateNoItemsAlert();
            attachItemEvents();
        });

        // Remove item
        function attachItemEvents() {
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.item-row').remove();
                    updateNoItemsAlert();
                    calculateTotal();
                };
            });

            // Auto-fill price when product is selected
            document.querySelectorAll('.product-select').forEach(select => {
                select.onchange = function() {
                    const option = this.options[this.selectedIndex];
                    const price = option.dataset.price || 0;
                    const row = this.closest('.item-row');
                    row.querySelector('.price-input').value = price;
                    calculateItemSubtotal(row);
                };
            });

            // Calculate on quantity/price change
            document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
                input.oninput = function() {
                    calculateItemSubtotal(this.closest('.item-row'));
                };
            });
        }

        // Calculate item subtotal
        function calculateItemSubtotal(row) {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
            calculateTotal();
        }

        // Calculate total
        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += qty * price;
            });

            const tax = subtotal * (taxRate / 100);
            const total = subtotal + tax;

            document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
            document.getElementById('taxDisplay').textContent = formatRupiah(tax);
            document.getElementById('totalDisplay').textContent = formatRupiah(total);
        }

        // Format Rupiah
        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update no items alert
        function updateNoItemsAlert() {
            const hasItems = document.querySelectorAll('.item-row').length > 0;
            document.getElementById('noItemsAlert').style.display = hasItems ? 'none' : 'block';
        }

        // Form validation
        document.getElementById('poForm').addEventListener('submit', function(e) {
            const itemCount = document.querySelectorAll('.item-row').length;
            if (itemCount === 0) {
                e.preventDefault();
                swalCoffee.fire({
                    title: 'Tambah Item',
                    text: 'Minimal harus ada 1 item produk!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });

        // Initialize
        updateNoItemsAlert();
    </script>
@endpush

