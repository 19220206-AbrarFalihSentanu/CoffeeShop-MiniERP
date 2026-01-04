{{-- File: resources/views/owner/financial/manual-expense.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Manual Expense')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="mb-4">
            <h4 class="fw-bold mb-2">
                <span class="text-muted fw-light">Financial /</span> Add Manual Expense
            </h4>
            <p class="text-muted">Record operational expenses manually</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Expense Details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('owner.financial.expense.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="operational" {{ old('category') == 'operational' ? 'selected' : '' }}>
                                        Operational (Listrik, Air, dll)
                                    </option>
                                    <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>
                                        Salary (Gaji Karyawan)
                                    </option>
                                    <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>
                                        Maintenance (Perawatan Alat)
                                    </option>
                                    <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>
                                        Marketing (Iklan, Promo)
                                    </option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>
                                        Other (Lain-lain)
                                    </option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="amount">Amount (Rp) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="amount" id="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}" min="0" step="1000" placeholder="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Enter amount without decimal</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="transaction_date">Transaction Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    class="form-control @error('transaction_date') is-invalid @enderror"
                                    value="{{ old('transaction_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                    required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="description">Description <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="4" minlength="10" maxlength="500" placeholder="Describe the expense in detail (min. 10 characters)"
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <span id="charCount">0</span>/500 characters (min. 10)
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save me-1'></i> Save Expense
                                </button>
                                <a href="{{ route('owner.financial.index') }}" class="btn btn-label-secondary">
                                    <i class='bx bx-x me-1'></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class='bx bx-info-circle me-1'></i> Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Use this form to record expenses that are not automatically tracked by the system, such as:
                        </p>
                        <ul class="text-muted small mb-0">
                            <li><strong>Operational:</strong> Electricity, water, internet bills</li>
                            <li><strong>Salary:</strong> Employee wages, bonuses</li>
                            <li><strong>Maintenance:</strong> Equipment repairs, cleaning</li>
                            <li><strong>Marketing:</strong> Ads, promotions, events</li>
                            <li><strong>Other:</strong> Miscellaneous expenses</li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class='bx bx-calendar me-1'></i> This Month's Expenses
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $thisMonthExpense = \App\Models\FinancialLog::expense()
                                ->whereMonth('transaction_date', now()->month)
                                ->whereYear('transaction_date', now()->year)
                                ->sum('amount');
                        @endphp
                        <h4 class="text-danger mb-0">
                            Rp {{ number_format($thisMonthExpense, 0, ',', '.') }}
                        </h4>
                        <small class="text-muted">Total expenses in {{ now()->format('F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Character counter for description
        document.getElementById('description').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Initialize counter on page load
        document.getElementById('charCount').textContent = document.getElementById('description').value.length;
    </script>
@endpush
