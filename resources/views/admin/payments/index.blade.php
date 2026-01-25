{{-- File: resources/views/admin/payments/index.blade.php --}}

@extends('layouts.app')

@section('title', __('payments.payment_verification'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-credit-card me-2"></i>{{ __('payments.payment_verification') }}</h4>
    </div>

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label col-form-label-sm">{{ __('payments.filter_by_status') }}:</label>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('payments.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('general.pending') }}
                        </option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>
                            {{ __('general.verified') }}
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            {{ __('general.rejected') }}
                        </option>
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bx bx-reset"></i> {{ __('general.reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Payments List --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Upload Pada</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="fw-bold text-primary">
                                        {{ $payment->order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $payment->order->customer_name }}</div>
                                    <small class="text-muted">{{ $payment->order->customer_email }}</small>
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $payment->payment_method_display }}
                                    </span>
                                </td>
                                <td>
                                    {{ $payment->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <span class="badge {{ $payment->status_badge_class }}">
                                        {{ $payment->status_display }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-show me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-info-circle bx-lg text-muted mb-2"></i>
                                    <p class="mb-0">Tidak ada pembayaran yang perlu diverifikasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <x-pagination-with-info :paginator="$payments" />
        </div>
    </div>
@endsection
