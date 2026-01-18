{{-- ============================================================ --}}
{{-- File: resources/views/admin/categories/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', __('categories.view_category'))

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }}
                    </a>
                </div>
                <div>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>{{ __('general.edit') }}
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                        data-confirm="{{ __('categories.confirm_delete_category') }}" data-confirm-title="Hapus Kategori?"
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
                            <h3 class="mb-2">{{ $category->name }}</h3>
                            <div>
                                <span class="badge bg-label-primary me-2">
                                    <code>{{ $category->slug }}</code>
                                </span>
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category->is_active ? __('general.active') : __('general.inactive') }}
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
                            <h6 class="text-primary mb-3">{{ __('categories.categories') }}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px">{{ __('general.name') }}</td>
                                    <td>: <strong>{{ $category->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">{{ __('categories.category_slug') }}</td>
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
                                    <td class="text-muted">{{ __('general.status') }}</td>
                                    <td>:
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? __('general.active') : __('general.inactive') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if ($category->description)
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">{{ __('general.description') }}</h6>
                                <p class="text-muted">{{ $category->description }}</p>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ __('general.created_at') }}: {{ $category->created_at->format('d/m/Y H:i') }} <br>
                            {{ __('general.updated_at') }}: {{ $category->updated_at->format('d/m/Y H:i') }}
                        </small>
                        <form action="{{ route('admin.categories.toggleStatus', $category) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-outline-{{ $category->is_active ? 'danger' : 'success' }} btn-sm">
                                <i class="bx bx-{{ $category->is_active ? 'x' : 'check' }}-circle me-1"></i>
                                {{ $category->is_active ? __('general.disabled') : __('general.enabled') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

