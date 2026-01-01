{{-- ============================================================ --}}
{{-- File: resources/views/admin/categories/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kategori Produk</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Tambah Kategori
        </a>
    </div>
    
    <div class="card-body">
        <!-- Filter & Search -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Cari kategori..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-search"></i> Cari
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
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Icon</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                @if($category->icon)
                                    <i class="bx {{ $category->icon }} bx-sm"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.categories.toggleStatus', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="badge border-0 {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.categories.show', $category) }}">
                                            <i class="bx bx-show me-1"></i> Lihat
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.categories.edit', $category) }}">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
