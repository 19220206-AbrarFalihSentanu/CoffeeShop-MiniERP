{{-- ============================================================ --}}
{{-- File: resources/views/owner/suppliers/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('suppliers.manage_suppliers'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('suppliers.supplier_list') }}</h5>
            <a href="{{ route('owner.suppliers.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> {{ __('suppliers.add_supplier') }}
            </a>
        </div>

        <div class="card-body">
            <!-- Filter & Search -->
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('general.all') }} {{ __('general.status') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                {{ __('general.active') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                {{ __('general.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('suppliers.all_types') }}</option>
                            <option value="petani" {{ request('type') == 'petani' ? 'selected' : '' }}>
                                {{ __('suppliers.type_petani') }}</option>
                            <option value="distributor" {{ request('type') == 'distributor' ? 'selected' : '' }}>
                                {{ __('suppliers.type_distributor') }}</option>
                            <option value="koperasi" {{ request('type') == 'koperasi' ? 'selected' : '' }}>
                                {{ __('suppliers.type_koperasi') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('suppliers.search_suppliers') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search"></i> {{ __('general.search') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('suppliers.supplier_code') }}</th>
                            <th>{{ __('suppliers.supplier_name') }}</th>
                            <th>{{ __('suppliers.contact_person') }}</th>
                            <th>{{ __('suppliers.type') }}</th>
                            <th>{{ __('general.phone') }}</th>
                            <th>{{ __('general.status') }}</th>
                            <th>{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                                <td><code>{{ $supplier->code }}</code></td>
                                <td><strong>{{ $supplier->name }}</strong></td>
                                <td>{{ $supplier->contact_person }}</td>
                                <td>
                                    @switch($supplier->type)
                                        @case('petani')
                                            <span class="badge bg-success">{{ __('suppliers.type_petani') }}</span>
                                        @break

                                        @case('distributor')
                                            <span class="badge bg-info">{{ __('suppliers.type_distributor') }}</span>
                                        @break

                                        @case('koperasi')
                                            <span class="badge bg-warning">{{ __('suppliers.type_koperasi') }}</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">{{ $supplier->type }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $supplier->phone }}</td>
                                <td>
                                    <form action="{{ route('owner.suppliers.toggleStatus', $supplier) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="badge border-0 {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $supplier->is_active ? __('general.active') : __('general.inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('owner.suppliers.show', $supplier) }}">
                                                <i class="bx bx-show me-1"></i> {{ __('general.view') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('owner.suppliers.edit', $supplier) }}">
                                                <i class="bx bx-edit me-1"></i> {{ __('general.edit') }}
                                            </a>
                                            <form action="{{ route('owner.suppliers.destroy', $supplier) }}" method="POST"
                                                onsubmit="return confirm('{{ __('suppliers.confirm_delete_supplier') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-1"></i> {{ __('general.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{ __('suppliers.no_suppliers') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    @endsection
