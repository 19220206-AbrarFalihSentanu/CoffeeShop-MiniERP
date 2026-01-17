{{-- ============================================================ --}}
{{-- File: resources/views/owner/categories/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('owner.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Kembali
                    </a>
                </div>
                <div>
                    <a href="{{ route('owner.categories.edit', $category) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                    <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" class="d-inline"
                        data-confirm="Kategori akan dihapus permanen!" data-confirm-title="Hapus Kategori?"
                        data-confirm-icon="warning" data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bx bx-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="mb-2">{{ $category->name }}</h3>
                            <div>
                                <span class="badge bg-label-primary me-2">
                                    <code>{{ $category->slug }}</code>
                                </span>
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            @if ($category->icon)
                                <i class="bx {{ $category->icon }} bx-xl"></i>
                            @else
                                <i class="bx bx-package bx-xl text-muted"></i>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Informasi Kategori</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px">Nama</td>
                                    <td>: <strong>{{ $category->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Slug</td>
                                    <td>: <code>{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Icon</td>
                                    <td>:
                                        @if ($category->icon)
                                            <i class="bx {{ $category->icon }}"></i> {{ $category->icon }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>:
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if ($category->description)
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Deskripsi</h6>
                                <p class="text-muted">{{ $category->description }}</p>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Dibuat: {{ $category->created_at->format('d/m/Y H:i') }} <br>
                            Diupdate: {{ $category->updated_at->format('d/m/Y H:i') }}
                        </small>
                        <form action="{{ route('owner.categories.toggleStatus', $category) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-outline-{{ $category->is_active ? 'danger' : 'success' }} btn-sm">
                                <i class="bx bx-{{ $category->is_active ? 'x' : 'check' }}-circle me-1"></i>
                                {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
