{{-- ============================================================ --}}
{{-- File: resources/views/admin/categories/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('categories.manage_categories'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('categories.category_list') }}</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> {{ __('categories.add_category') }}
            </a>
        </div>

        <div class="card-body">
            <!-- Filter & Search -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('general.all') }} {{ __('general.status') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                {{ __('general.active') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                {{ __('general.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('categories.search_categories') }}" value="{{ request('search') }}">
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
                            <th>{{ __('categories.category_name') }}</th>
                            <th>{{ __('categories.category_slug') }}</th>
                            <th>Icon</th>
                            <th>{{ __('general.description') }}</th>
                            <th>{{ __('general.status') }}</th>
                            <th>{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    @if ($category->icon)
                                        <i class="bx {{ $category->icon }} bx-sm"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.categories.toggleStatus', $category) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="badge border-0 {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? __('general.active') : __('general.inactive') }}
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
                                            <a class="dropdown-item"
                                                href="{{ route('admin.categories.show', $category) }}">
                                                <i class="bx bx-show me-1"></i> {{ __('general.view') }}
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.categories.edit', $category) }}">
                                                <i class="bx bx-edit me-1"></i> {{ __('general.edit') }}
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                                method="POST"
                                                data-confirm="{{ __('categories.confirm_delete_category') }}"
                                                data-confirm-title="Hapus Kategori?" data-confirm-icon="warning"
                                                data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
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
                                <td colspan="7" class="text-center">{{ __('categories.no_categories') }}</td>
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
