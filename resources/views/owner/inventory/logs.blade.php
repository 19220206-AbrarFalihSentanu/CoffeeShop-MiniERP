{{-- File: resources/views/owner/inventory/logs.blade.php --}}

@extends('layouts.app')

@section('title', __('inventory.inventory_logs'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-history me-2"></i>{{ __('inventory.inventory_logs') }}
            </h5>
            <div>
                <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }}
                </a>
                <a href="{{ route('owner.inventory.export') }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download me-1"></i>{{ __('general.export') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Advanced Filters --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label small">{{ __('products.product') }}</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">{{ __('products.all_categories') }}</option>
                            @foreach ($products as $prod)
                                <option value="{{ $prod->id }}"
                                    {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->name }} ({{ $prod->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">{{ __('general.type') }}</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>
                                {{ __('inventory.stock_in') }}</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>
                                {{ __('inventory.stock_out') }}</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>
                                {{ __('inventory.adjustment') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">{{ __('financial.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">{{ __('financial.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">{{ __('general.search') }}</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="{{ __('inventory.reference') }}/{{ __('inventory.notes') }}..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="width: 140px">{{ __('general.date') }}</th>
                            <th>{{ __('products.product') }}</th>
                            <th>{{ __('inventory.sku') }}</th>
                            <th style="width: 100px">{{ __('general.type') }}</th>
                            <th style="width: 80px">{{ __('general.quantity') }}</th>
                            <th style="width: 80px">{{ __('general.before') }}</th>
                            <th style="width: 80px">{{ __('general.after') }}</th>
                            <th>{{ __('users.users') }}</th>
                            <th>{{ __('inventory.reference') }}</th>
                            <th>{{ __('inventory.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small>
                                        {{ $log->created_at->format('d/m/Y') }}
                                        <br>
                                        <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $log->product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $log->product->weight }}g</small>
                                </td>
                                <td>
                                    <code class="small">{{ $log->product->sku }}</code>
                                </td>
                                <td>
                                    @if ($log->type === 'in')
                                        <span class="badge bg-success">
                                            <i class="bx bx-plus-circle"></i> Stock In
                                        </span>
                                    @elseif($log->type === 'out')
                                        <span class="badge bg-danger">
                                            <i class="bx bx-minus-circle"></i> Stock Out
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bx bx-adjust"></i> Adjustment
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                    </strong>
                                </td>
                                <td>{{ $log->before }}</td>
                                <td>
                                    <strong>{{ $log->after }}</strong>
                                </td>
                                <td>
                                    <small>
                                        {{ $log->user->name }}
                                        <br>
                                        <span class="text-muted">{{ $log->user->role->display_name }}</span>
                                    </small>
                                </td>
                                <td>
                                    @if ($log->reference)
                                        <code class="small">{{ $log->reference }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->notes ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bx bx-history bx-lg text-muted"></i>
                                    <p class="text-muted mt-2">{{ __('inventory.no_logs') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $logs->appends(request()->query())->links() }}
            </div>

            {{-- Summary Info --}}
            @if ($logs->count() > 0)
                <div class="alert alert-info mt-3">
                    <i class="bx bx-info-circle me-1"></i>
                    Menampilkan <strong>{{ $logs->count() }}</strong> dari <strong>{{ $logs->total() }}</strong>
                    riwayat transaksi
                </div>
            @endif
        </div>
    </div>
@endsection
