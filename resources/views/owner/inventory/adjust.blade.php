{{-- File: resources/views/owner/inventory/adjust.blade.php --}}

@extends('layouts.app')

@section('title', 'Adjust Stok - ' . $product->name)

@push('styles')
    <style>
        .stock-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .stock-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stock-type-card.active {
            border-color: #696cff;
            background-color: #f8f9fa;
        }

        .stock-type-card input[type="radio"] {
            display: none;
        }

        .product-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            {{-- Back Button --}}
            <div class="mb-3">
                <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Kembali ke Inventory
                </a>
            </div>

            <div class="row">
                {{-- Product Info Card --}}
                <div class="col-md-4 mb-4">
                    <div class="card product-info-card h-100">
                        <div class="card-body text-center">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded mb-3" style="max-height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-white bg-opacity-25 rounded d-flex align-items-center justify-content-center mb-3"
                                    style="height: 200px;">
                                    <i class="bx bx-image bx-lg"></i>
                                </div>
                            @endif

                            <h5 class="mb-2">{{ $product->name }}</h5>
                            <p class="mb-3">
                                <span class="badge bg-white text-primary">{{ $product->category->name }}</span>
                                <span class="badge bg-white text-primary">{{ $product->sku }}</span>
                            </p>

                            <hr class="border-white">

                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <small class="d-block opacity-75">Stok Total</small>
                                    <h4 class="mb-0">{{ $product->inventory ? $product->inventory->quantity : 0 }}</h4>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="d-block opacity-75">Reserved</small>
                                    <h4 class="mb-0">{{ $product->inventory ? $product->inventory->reserved : 0 }}</h4>
                                </div>
                                <div class="col-6">
                                    <small class="d-block opacity-75">Tersedia</small>
                                    <h4 class="mb-0">{{ $product->getAvailableStock() }}</h4>
                                </div>
                                <div class="col-6">
                                    <small class="d-block opacity-75">Min. Stok</small>
                                    <h4 class="mb-0">{{ $product->min_stock }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Adjustment Form --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bx bx-adjust me-2"></i>Adjust Stok Produk
                            </h5>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('owner.inventory.processAdjustment', $product) }}" method="POST"
                                id="adjustForm">
                                @csrf

                                {{-- Pilih Tipe Adjustment --}}
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-list-ul me-1"></i>Pilih Tipe Transaksi
                                </h6>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="stock-type-card card h-100 mb-0" onclick="selectType('in')">
                                            <div class="card-body text-center">
                                                <input type="radio" name="type" value="in" required>
                                                <i class="bx bx-plus-circle bx-lg text-success mb-2"></i>
                                                <h6 class="mb-1">Stock In</h6>
                                                <small class="text-muted">Tambah stok masuk</small>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="stock-type-card card h-100 mb-0" onclick="selectType('out')">
                                            <div class="card-body text-center">
                                                <input type="radio" name="type" value="out" required>
                                                <i class="bx bx-minus-circle bx-lg text-danger mb-2"></i>
                                                <h6 class="mb-1">Stock Out</h6>
                                                <small class="text-muted">Kurangi stok keluar</small>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="stock-type-card card h-100 mb-0" onclick="selectType('adjustment')">
                                            <div class="card-body text-center">
                                                <input type="radio" name="type" value="adjustment" required>
                                                <i class="bx bx-adjust bx-lg text-warning mb-2"></i>
                                                <h6 class="mb-1">Adjustment</h6>
                                                <small class="text-muted">Set stok manual</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                {{-- Input Quantity --}}
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-list-ol me-1"></i>Detail Transaksi
                                </h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">
                                                Jumlah ({{ $product->unit ?? 'kg' }}) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                                name="quantity" value="{{ old('quantity') }}" min="0.001" step="0.001"
                                                required placeholder="Masukkan jumlah">
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted" id="quantityHint">Masukkan jumlah dalam
                                                {{ $product->unit ?? 'kg' }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="reference" class="form-label">
                                                Reference / PO Number
                                            </label>
                                            <input type="text"
                                                class="form-control @error('reference') is-invalid @enderror"
                                                id="reference" name="reference" value="{{ old('reference') }}"
                                                placeholder="Contoh: PO-2025-001">
                                            @error('reference')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Nomor referensi (opsional)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                        placeholder="Tambahkan catatan tentang transaksi ini...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Jelaskan alasan adjustment (opsional)</small>
                                </div>

                                {{-- Preview Result --}}
                                <div id="previewSection" class="alert alert-info" style="display: none;">
                                    <h6 class="alert-heading mb-2">
                                        <i class="bx bx-info-circle me-1"></i>Preview Hasil
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Stok Sekarang:</small>
                                            <br>
                                            <strong
                                                id="currentStock">{{ $product->inventory ? $product->inventory->quantity : 0 }}</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Perubahan:</small>
                                            <br>
                                            <strong id="changeAmount" class="text-primary">0</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Stok Setelah:</small>
                                            <br>
                                            <strong id="afterStock"
                                                class="text-success">{{ $product->inventory ? $product->inventory->quantity : 0 }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- Action Buttons --}}
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-x me-1"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bx bx-check me-1"></i>Proses Adjustment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const currentStock = {{ $product->inventory ? $product->inventory->quantity : 0 }};
        const availableStock = {{ $product->getAvailableStock() }};

        // Select transaction type
        function selectType(type) {
            // Remove active class from all cards
            document.querySelectorAll('.stock-type-card').forEach(card => {
                card.classList.remove('active');
            });

            // Add active class to selected card
            event.currentTarget.classList.add('active');

            // Update hint based on type
            const quantityHint = document.getElementById('quantityHint');
            if (type === 'in') {
                quantityHint.textContent = 'Masukkan jumlah unit yang akan ditambahkan';
            } else if (type === 'out') {
                quantityHint.textContent = `Masukkan jumlah unit yang akan dikurangi (max: ${availableStock})`;
            } else {
                quantityHint.textContent = 'Masukkan total stok baru yang diinginkan';
            }

            updatePreview();
        }

        // Update preview when quantity changes
        document.getElementById('quantity').addEventListener('input', updatePreview);
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        function updatePreview() {
            const type = document.querySelector('input[name="type"]:checked');
            const quantity = parseInt(document.getElementById('quantity').value) || 0;

            if (!type || quantity === 0) {
                document.getElementById('previewSection').style.display = 'none';
                return;
            }

            document.getElementById('previewSection').style.display = 'block';

            let afterStock = currentStock;
            let changeAmount = 0;

            if (type.value === 'in') {
                afterStock = currentStock + quantity;
                changeAmount = `+${quantity}`;
                document.getElementById('changeAmount').className = 'text-success';
            } else if (type.value === 'out') {
                afterStock = currentStock - quantity;
                changeAmount = `-${quantity}`;
                document.getElementById('changeAmount').className = 'text-danger';

                // Validation
                if (quantity > availableStock) {
                    document.getElementById('previewSection').className = 'alert alert-danger';
                    document.getElementById('submitBtn').disabled = true;
                } else {
                    document.getElementById('previewSection').className = 'alert alert-info';
                    document.getElementById('submitBtn').disabled = false;
                }
            } else { // adjustment
                afterStock = quantity;
                changeAmount = quantity - currentStock;
                if (changeAmount > 0) {
                    changeAmount = `+${changeAmount}`;
                    document.getElementById('changeAmount').className = 'text-success';
                } else if (changeAmount < 0) {
                    document.getElementById('changeAmount').className = 'text-danger';
                } else {
                    document.getElementById('changeAmount').className = 'text-muted';
                }
            }

            document.getElementById('currentStock').textContent = currentStock;
            document.getElementById('changeAmount').textContent = changeAmount;
            document.getElementById('afterStock').textContent = afterStock;
        }

        // Form validation
        document.getElementById('adjustForm').addEventListener('submit', function(e) {
            const type = document.querySelector('input[name="type"]:checked');
            const quantity = parseInt(document.getElementById('quantity').value) || 0;

            if (!type) {
                e.preventDefault();
                alert('Silakan pilih tipe transaksi terlebih dahulu!');
                return false;
            }

            if (type.value === 'out' && quantity > availableStock) {
                e.preventDefault();
                alert(`Stok tidak mencukupi! Stok tersedia: ${availableStock} unit`);
                return false;
            }

            return confirm('Apakah Anda yakin ingin memproses adjustment ini?');
        });
    </script>
@endpush
