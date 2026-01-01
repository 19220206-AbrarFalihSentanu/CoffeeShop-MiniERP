{{-- File: resources/views/admin/purchase-orders/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@push('styles')
    <style>
        .item-row {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Purchase Order: {{ $purchaseOrder->po_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.purchase-orders.update', $purchaseOrder) }}" method="POST" id="poForm">
                        @csrf
                        @method('PUT')

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
                                            {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                                <label for="expected_delivery_date" class="form-label">Tanggal Pengiriman <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('expected_delivery_date') is-invalid @enderror"
                                    id="expected_delivery_date" name="expected_delivery_date"
                                    value="{{ old('expected_delivery_date', $purchaseOrder->expected_delivery_date->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('expected_delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Catatan untuk Owner</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $purchaseOrder->notes) }}</textarea>
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
                            @foreach ($purchaseOrder->items as $index => $item)
                                <div class="item-row" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Produk <span class="text-danger">*</span></label>
                                            <select class="form-select product-select"
                                                name="items[{{ $index }}][product_id]" required>
                                                <option value="">Pilih Produk</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-price="{{ $product->cost_price ?? $product->price }}"
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - {{ $product->category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quantity (kg) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control quantity-input"
                                                name="items[{{ $index }}][quantity]"
                                                value="{{ $item->quantity_ordered }}" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Harga/kg <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control price-input"
                                                name="items[{{ $index }}][unit_price]"
                                                value="{{ $item->unit_price }}" min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-item">
                                                <i class='bx bx-trash'></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <small class="text-muted">Subtotal: <strong class="item-subtotal">Rp
                                                    {{ number_format($item->subtotal, 0, ',', '.') }}</strong></small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <strong id="subtotalDisplay">Rp
                                                {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Pajak ({{ setting('tax_rate', 11) }}%):</span>
                                            <strong id="taxDisplay">Rp
                                                {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">TOTAL:</h5>
                                            <h5 class="mb-0 text-primary" id="totalDisplay">Rp
                                                {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>

                            <div>
                                {{-- Button Update (selalu ada) --}}
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Update Purchase Order
                                </button>

                                {{-- Button Submit untuk Approval (hanya untuk PO rejected) --}}
                                @if ($purchaseOrder->status === 'rejected')
                                    <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal"
                                        data-bs-target="#submitModal">
                                        <i class='bx bx-send'></i> Submit untuk Approval
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Submit untuk Approval --}}
    @if ($purchaseOrder->status === 'rejected')
        <div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class='bx bx-send'></i> Submit untuk Approval
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.purchase-orders.submit', $purchaseOrder) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class='bx bx-info-circle'></i>
                                Sebelum submit, pastikan semua perubahan sudah di-<strong>Update</strong> terlebih dahulu.
                            </div>

                            <p><strong>Nomor PO:</strong> {{ $purchaseOrder->po_number }}</p>
                            <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                            </p>

                            <hr>

                            <p class="text-muted mb-0">
                                <small>
                                    <i class='bx bx-info-circle'></i>
                                    PO akan dikirim ke Owner untuk approval. Owner akan menerima email notifikasi.
                                </small>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class='bx bx-send'></i> Ya, Submit Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        let itemIndex = {{ $purchaseOrder->items->count() }};
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
            attachItemEvents();
        });

        // Remove item & calculate
        function attachItemEvents() {
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.item-row').remove();
                    calculateTotal();
                };
            });

            document.querySelectorAll('.product-select').forEach(select => {
                select.onchange = function() {
                    const option = this.options[this.selectedIndex];
                    const price = option.dataset.price || 0;
                    const row = this.closest('.item-row');
                    row.querySelector('.price-input').value = price;
                    calculateItemSubtotal(row);
                };
            });

            document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
                input.oninput = function() {
                    calculateItemSubtotal(this.closest('.item-row'));
                };
            });
        }

        function calculateItemSubtotal(row) {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
            calculateTotal();
        }

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

        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Initialize
        attachItemEvents();
        calculateTotal();

        // Form validation
        document.getElementById('poForm').addEventListener('submit', function(e) {
            const itemCount = document.querySelectorAll('.item-row').length;
            if (itemCount === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 item produk!');
                return false;
            }
        });
    </script>
@endpush
