{{-- ============================================================ --}}
{{-- File: resources/views/owner/categories/edit.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Kategori: {{ $category->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
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
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="Deskripsi singkat tentang kategori ini">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.categories.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Update Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
