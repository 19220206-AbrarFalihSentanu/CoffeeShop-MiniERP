{{-- ============================================================ --}}
{{-- File: resources/views/admin/categories/edit.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('categories.edit_category'))

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('categories.edit_category') }}: {{ $category->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('categories.category_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $category->name) }}" placeholder="Contoh: Arabica"
                                required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon Boxicons</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon"
                                name="icon" value="{{ old('icon', $category->icon) }}" placeholder="Contoh: bx-coffee">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Gunakan class dari <a href="https://boxicons.com/"
                                    target="_blank">Boxicons</a>. Contoh: bx-coffee, bx-coffee-togo</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('categories.category_description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="{{ __('categories.category_description') }}">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
