{{-- File: resources/views/admin/purchase-orders/receive/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Receive Stock - ' . $purchaseOrder->po_number)

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- Header Card --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-2">Receive Stock: {{ $purchaseOrder->po_number }}</h4>
                            <p class="mb-0 text-muted">Supplier: <strong>{{ $purchaseOrder->supplier->name }}</strong></p>
                        </div>
                        <div>
                            <a href="{{ route('admin.purchase-orders.receive.index') }}" class="btn btn-secondary btn-sm">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Receive Form --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Penerimaan Barang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.purchase-orders.receive.store', $purchaseOrder) }}" method="POST"
                        id="receiveForm">
                        @csrf

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            <strong>Petunjuk:</strong> Input jumlah yang diterima untuk setiap item.
                            Jika ada item yang belum diterima, biarkan kosong atau isi 0.
                            Stok akan otomatis bertambah sesuai jumlah yang Anda input.
                        </div>

                        {{-- Items Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">
                                            <input type="checkbox" id="checkAll" class="form-check-input">
                                        </th>
                                        <th style="width: 30%;">Produk</th>
                                        <th style="width: 15%;" class="text-center">Qty Dipesan</th>
                                        <th style="width: 15%;" class="text-center">Sudah Diterima</th>
                                        <th style="width: 15%;" class="text-center">Sisa</th>
                                        <th style="width: 20%;" class="text-center">Terima Sekarang (kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->items as $item)
                                        <tr class="{{ $item->isFullyReceived() ? 'table-success' : '' }}">
                                            <td class="text-center">
                                                @if (!$item->isFullyReceived())
                                                    <input type="checkbox" class="form-check-input item-checkbox"
                                                        data-item-id="{{ $item->id }}">
                                                @else
                                                    <i class='bx bx-check-circle text-success fs-5'></i>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item->product->name }}</strong><br>
                                                <small class="text-muted">{{ $item->product->category->name }}</small><br>
                                                <small class="text-muted">Stok Saat Ini:
                                                    {{ $item->product->inventory->quantity ?? 0 }} kg</small>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item->quantity_ordered) }} kg</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">
                                                    {{ number_format($item->quantity_received) }} kg
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($item->remaining_quantity > 0)
                                                    <span class="badge bg-warning">
                                                        {{ number_format($item->remaining_quantity) }} kg
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">Lengkap</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$item->isFullyReceived())
                                                    <input type="hidden" name="items[{{ $loop->index }}][item_id]"
                                                        value="{{ $item->id }}">
                                                    <input type="number" class="form-control quantity-receive-input"
                                                        name="items[{{ $loop->index }}][quantity_received]" min="0"
                                                        max="{{ $item->remaining_quantity }}" value="0"
                                                        data-max="{{ $item->remaining_quantity }}">
                                                    <small class="text-muted">Max:
                                                        {{ number_format($item->remaining_quantity) }} kg</small>
                                                @else
                                                    <span class="badge bg-success">Sudah Lengkap</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Notes --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label for="notes" class="form-label">Catatan Penerimaan (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                    placeholder="Contoh: Barang diterima dalam kondisi baik. Ada 2 karung dengan kemasan rusak."></textarea>
                            </div>
                        </div>

                        {{-- Progress Info --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">Progress Penerimaan</h6>
                                        <div class="progress mb-2" style="height: 25px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $purchaseOrder->receive_progress }}%">
                                                {{ number_format($purchaseOrder->receive_progress, 1) }}%
                                            </div>
                                        </div>
                                        <p class="mb-0 text-muted">
                                            <i class='bx bx-info-circle'></i>
                                            @if ($purchaseOrder->receive_progress >= 100)
                                                Semua item telah diterima lengkap. PO akan otomatis berstatus "Completed".
                                            @else
                                                Jika semua item sudah diterima lengkap, PO akan otomatis berstatus
                                                "Completed".
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.purchase-orders.receive.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class='bx bx-package'></i> Simpan Penerimaan Stock
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
        // Check All functionality
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const isChecked = this.checked;

            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                toggleQuantityInput(checkbox);
            });
        });

        // Individual checkbox
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleQuantityInput(this);
            });
        });

        function toggleQuantityInput(checkbox) {
            const row = checkbox.closest('tr');
            const input = row.querySelector('.quantity-receive-input');

            if (checkbox.checked) {
                const maxQty = input.dataset.max;
                input.value = maxQty;
                input.classList.add('border-success');
            } else {
                input.value = 0;
                input.classList.remove('border-success');
            }
        }

        // Validate quantities
        document.querySelectorAll('.quantity-receive-input').forEach(input => {
            input.addEventListener('input', function() {
                const max = parseFloat(this.dataset.max);
                const value = parseFloat(this.value);

                if (value > max) {
                    this.value = max;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jumlah Melebihi Batas',
                        text: `Jumlah tidak boleh melebihi ${max} kg`,
                        confirmButtonColor: '#8B5A2B'
                    });
                }

                if (value < 0) {
                    this.value = 0;
                }
            });
        });

        // Form validation
        document.getElementById('receiveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const inputs = document.querySelectorAll('.quantity-receive-input');
            let totalReceived = 0;

            inputs.forEach(input => {
                totalReceived += parseFloat(input.value) || 0;
            });

            if (totalReceived === 0) {
                swalCoffee.fire({
                    title: 'Input Jumlah',
                    text: 'Minimal harus ada 1 item yang diterima! Silakan input jumlah yang diterima.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            swalCoffee.fire({
                title: 'Terima Stock?',
                text: 'Anda akan menerima stock untuk item yang diinput.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush


