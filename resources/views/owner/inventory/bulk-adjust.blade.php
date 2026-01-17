{{-- File: resources/views/owner/inventory/bulk-adjust.blade.php --}}

@extends('layouts.app')

@section('title', 'Bulk Stock Adjustment')

@push('styles')
    <style>
        .product-row:hover {
            background-color: #f8f9fa;
        }

        .stock-input {
            width: 100px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bx bx-spreadsheet me-2"></i>Bulk Stock Adjustment
                </h5>
                <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-1"></i>
                <strong>Petunjuk:</strong> Update stok untuk multiple produk sekaligus. Masukkan nilai baru untuk setiap
                produk yang ingin diubah, lalu klik "Proses Bulk Adjustment".
            </div>

            <form action="{{ route('owner.inventory.processBulkAdjustment') }}" method="POST" id="bulkAdjustForm">
                @csrf

                {{-- Global Notes --}}
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="notes" class="form-label">Catatan Global (Opsional)</label>
                        <input type="text" class="form-control" id="notes" name="notes"
                            placeholder="Contoh: Stock Opname Akhir Bulan Desember 2025">
                        <small class="text-muted">Catatan ini akan diterapkan ke semua adjustment</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-info btn-sm me-2" onclick="selectAll()">
                                <i class="bx bx-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetAll()">
                                <i class="bx bx-reset"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Search/Filter --}}
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchProduct"
                        placeholder="Cari produk (nama atau SKU)..." onkeyup="filterProducts()">
                </div>

                {{-- Products Table --}}
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox"
                                        onchange="toggleAllCheckboxes(this)">
                                </th>
                                <th style="width: 60px">Gambar</th>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th>Kategori</th>
                                <th style="width: 100px">Stok Sekarang</th>
                                <th style="width: 100px">Reserved</th>
                                <th style="width: 100px">Tersedia</th>
                                <th style="width: 120px">Stok Baru</th>
                                <th style="width: 100px">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            @foreach ($products as $product)
                                <tr class="product-row" data-product-name="{{ strtolower($product->name) }}"
                                    data-product-sku="{{ strtolower($product->sku) }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input product-checkbox"
                                            name="selected_products[]" value="{{ $product->id }}"
                                            onchange="toggleInputField(this, {{ $product->id }})">
                                    </td>
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bx bx-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->weight }}g</small>
                                    </td>
                                    <td><code class="small">{{ $product->sku }}</code></td>
                                    <td>
                                        <span class="badge bg-info small">{{ $product->category->name }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-secondary current-stock-{{ $product->id }}">{{ $product->inventory ? $product->inventory->quantity : 0 }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted">{{ $product->inventory ? $product->inventory->reserved : 0 }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $available = $product->inventory ? $product->inventory->available : 0;
                                        @endphp
                                        <span
                                            class="badge {{ $available <= 0 ? 'bg-danger' : ($product->isLowStock() ? 'bg-warning' : 'bg-success') }}">
                                            {{ $available }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number"
                                            class="form-control form-control-sm stock-input new-stock-input"
                                            id="stock-{{ $product->id }}" data-product-id="{{ $product->id }}"
                                            value="{{ $product->inventory ? $product->inventory->quantity : 0 }}"
                                            min="0" disabled
                                            onchange="calculateChange({{ $product->id }}, {{ $product->inventory ? $product->inventory->quantity : 0 }})">
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary change-indicator-{{ $product->id }}">0</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary Section --}}
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Produk:</strong> <span id="totalProducts">{{ $products->count() }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Produk Terpilih:</strong> <span id="selectedCount">0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Perubahan:</strong> <span id="totalChanges">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary">
                        <i class="bx bx-x me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="bx bx-check me-1"></i>Proses Bulk Adjustment (<span id="btnSelectedCount">0</span>
                        produk)
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle checkbox select all
        function toggleAllCheckboxes(checkbox) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                toggleInputField(cb, cb.value);
            });
            updateSummary();
        }

        // Toggle input field based on checkbox
        function toggleInputField(checkbox, productId) {
            const input = document.getElementById(`stock-${productId}`);
            if (checkbox.checked) {
                input.disabled = false;
                input.focus();
            } else {
                input.disabled = true;
                // Reset to current stock
                const currentStock = document.querySelector(`.current-stock-${productId}`).textContent;
                input.value = currentStock;
                calculateChange(productId, parseInt(currentStock));
            }
            updateSummary();
        }

        // Calculate change indicator
        function calculateChange(productId, currentStock) {
            const newStock = parseInt(document.getElementById(`stock-${productId}`).value) || 0;
            const change = newStock - currentStock;
            const indicator = document.querySelector(`.change-indicator-${productId}`);

            if (change > 0) {
                indicator.textContent = `+${change}`;
                indicator.className = 'badge bg-success';
            } else if (change < 0) {
                indicator.textContent = change;
                indicator.className = 'badge bg-danger';
            } else {
                indicator.textContent = '0';
                indicator.className = 'badge bg-secondary';
            }

            updateSummary();
        }

        // Update summary
        function updateSummary() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const count = checkboxes.length;

            document.getElementById('selectedCount').textContent = count;
            document.getElementById('btnSelectedCount').textContent = count;

            // Enable/disable submit button
            document.getElementById('submitBtn').disabled = count === 0;

            // Calculate total changes
            let totalChanges = 0;
            checkboxes.forEach(cb => {
                const productId = cb.value;
                const changeIndicator = document.querySelector(`.change-indicator-${productId}`);
                const changeText = changeIndicator.textContent;
                const changeValue = parseInt(changeText.replace('+', ''));
                if (!isNaN(changeValue)) {
                    totalChanges += Math.abs(changeValue);
                }
            });
            document.getElementById('totalChanges').textContent = totalChanges;
        }

        // Select all products
        function selectAll() {
            document.getElementById('selectAllCheckbox').checked = true;
            toggleAllCheckboxes(document.getElementById('selectAllCheckbox'));
        }

        // Reset all
        function resetAll() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
                toggleInputField(cb, cb.value);
            });
            document.getElementById('selectAllCheckbox').checked = false;
            updateSummary();
        }

        // Filter products
        function filterProducts() {
            const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const name = row.getAttribute('data-product-name');
                const sku = row.getAttribute('data-product-sku');

                if (name.includes(searchTerm) || sku.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Form validation
        document.getElementById('bulkAdjustForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');

            if (checkboxes.length === 0) {
                swalCoffee.fire({
                    title: 'Pilih Produk',
                    text: 'Silakan pilih minimal satu produk untuk di-adjust!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Build adjustments array from checked products
            let adjustmentIndex = 0;
            checkboxes.forEach(cb => {
                const productId = cb.value;
                const quantity = document.getElementById(`stock-${productId}`).value;

                // Create hidden inputs for selected products
                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `adjustments[${adjustmentIndex}][quantity]`;
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = `adjustments[${adjustmentIndex}][product_id]`;
                productIdInput.value = productId;
                form.appendChild(productIdInput);

                adjustmentIndex++;
            });

            swalCoffee.fire({
                title: 'Proses Bulk Adjustment?',
                text: `Anda akan memproses bulk adjustment untuk ${checkboxes.length} produk.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Initialize summary on load
        updateSummary();
    </script>
@endpush
