{{-- ============================================================ --}}
{{-- File: resources/views/owner/suppliers/edit.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('suppliers.edit_supplier'))

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('suppliers.edit_supplier') }}: {{ $supplier->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Supplier Info --}}
                        <h6 class="text-primary mb-3">{{ __('suppliers.supplier_info') }}</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="code" class="form-label">{{ __('suppliers.supplier_code') }}</label>
                                <input type="text" class="form-control" id="code" value="{{ $supplier->code }}"
                                    disabled>
                                <small class="text-muted">Kode tidak dapat diubah</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">{{ __('suppliers.supplier_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">{{ __('suppliers.type') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="petani"
                                        {{ old('type', $supplier->type) == 'petani' ? 'selected' : '' }}>
                                        {{ __('suppliers.type_petani') }}</option>
                                    <option value="distributor"
                                        {{ old('type', $supplier->type) == 'distributor' ? 'selected' : '' }}>
                                        {{ __('suppliers.type_distributor') }}</option>
                                    <option value="koperasi"
                                        {{ old('type', $supplier->type) == 'koperasi' ? 'selected' : '' }}>
                                        {{ __('suppliers.type_koperasi') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <h6 class="text-primary mb-3 mt-4">{{ __('suppliers.contact_info') }}</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">{{ __('suppliers.contact_person') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                    id="contact_person" name="contact_person"
                                    value="{{ old('contact_person', $supplier->contact_person) }}" required>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('suppliers.phone') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('suppliers.email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $supplier->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Address Info --}}
                        <h6 class="text-primary mb-3 mt-4">{{ __('suppliers.address_info') }}</h6>

                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('suppliers.address') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                required>{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">{{ __('suppliers.city') }}</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" value="{{ old('city', $supplier->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">{{ __('suppliers.province') }}</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror"
                                    id="province" name="province" value="{{ old('province', $supplier->province) }}">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">{{ __('suppliers.postal_code') }}</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                    id="postal_code" name="postal_code"
                                    value="{{ old('postal_code', $supplier->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('suppliers.notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.suppliers.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> {{ __('general.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> {{ __('general.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

