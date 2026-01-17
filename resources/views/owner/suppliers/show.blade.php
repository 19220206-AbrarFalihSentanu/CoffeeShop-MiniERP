{{-- ============================================================ --}}
{{-- File: resources/views/owner/suppliers/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('suppliers.view_supplier'))

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('owner.suppliers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }}
                    </a>
                </div>
                <div>
                    <a href="{{ route('owner.suppliers.edit', $supplier) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>{{ __('general.edit') }}
                    </a>
                    <form action="{{ route('owner.suppliers.destroy', $supplier) }}" method="POST" class="d-inline"
                        data-confirm="{{ __('suppliers.confirm_delete_supplier') }}" data-confirm-title="Hapus Supplier?"
                        data-confirm-icon="warning" data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bx bx-trash me-1"></i>{{ __('general.delete') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="mb-2">{{ $supplier->name }}</h3>
                            <div>
                                <span class="badge bg-label-primary me-2">
                                    <code>{{ $supplier->code }}</code>
                                </span>
                                @switch($supplier->type)
                                    @case('petani')
                                        <span class="badge bg-success me-2">{{ __('suppliers.type_petani') }}</span>
                                    @break

                                    @case('distributor')
                                        <span class="badge bg-info me-2">{{ __('suppliers.type_distributor') }}</span>
                                    @break

                                    @case('koperasi')
                                        <span class="badge bg-warning me-2">{{ __('suppliers.type_koperasi') }}</span>
                                    @break
                                @endswitch
                                <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier->is_active ? __('general.active') : __('general.inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <i class="bx bx-store-alt bx-xl text-primary"></i>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">{{ __('suppliers.contact_info') }}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 140px">{{ __('suppliers.contact_person') }}</td>
                                    <td>: <strong>{{ $supplier->contact_person }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('general.email') }}</td>
                                    <td>: <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('general.phone') }}</td>
                                    <td>: <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">{{ __('suppliers.address_info') }}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 100px">{{ __('suppliers.address') }}</td>
                                    <td>: {{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('suppliers.city') }}</td>
                                    <td>: {{ $supplier->city ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('suppliers.province') }}</td>
                                    <td>: {{ $supplier->province ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('suppliers.postal_code') }}</td>
                                    <td>: {{ $supplier->postal_code ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($supplier->notes)
                        <hr>
                        <div>
                            <h6 class="text-primary mb-3">{{ __('suppliers.notes') }}</h6>
                            <p class="text-muted">{{ $supplier->notes }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                {{ __('general.created_at') }}: {{ $supplier->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                {{ __('general.updated_at') }}: {{ $supplier->updated_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Purchase Orders History --}}
            @if ($supplier->purchaseOrders->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('menu.purchase_orders') }} ({{ $supplier->purchaseOrders->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No. PO</th>
                                        <th>{{ __('general.date') }}</th>
                                        <th>{{ __('general.status') }}</th>
                                        <th>{{ __('general.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->purchaseOrders->take(10) as $po)
                                        <tr>
                                            <td><code>{{ $po->po_number }}</code></td>
                                            <td>{{ $po->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $po->status == 'completed' ? 'success' : ($po->status == 'approved' ? 'info' : ($po->status == 'pending' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($po->status) }}
                                                </span>
                                            </td>
                                            <td>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
