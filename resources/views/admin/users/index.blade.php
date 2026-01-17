{{-- File: resources/views/admin/users/index.blade.php --}}

@extends('layouts.app')

@section('title', __('users.manage_users'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('users.user_list') }}</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> {{ __('users.add_user') }}
            </a>
        </div>

        <div class="card-body">
            <!-- Filter & Search -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="role" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('users.all_roles') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('users.search_users') }}" value="{{ request('search') }}">
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
                            <th>{{ __('users.name') }}</th>
                            <th>{{ __('users.email') }}</th>
                            <th>{{ __('users.role') }}</th>
                            <th>{{ __('users.phone') }}</th>
                            <th>{{ __('general.status') }}</th>
                            <th>{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge 
                                @if ($user->role->name == 'owner') bg-primary
                                @elseif($user->role->name == 'admin') bg-info
                                @else bg-secondary @endif">
                                        {{ $user->role->display_name }}
                                    </span>
                                </td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="badge border-0 {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? __('general.active') : __('general.inactive') }}
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
                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                <i class="bx bx-show me-1"></i> {{ __('general.view') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                <i class="bx bx-edit me-1"></i> {{ __('general.edit') }}
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    data-confirm="{{ __('users.confirm_delete_user') }}"
                                                    data-confirm-title="Hapus User?" data-confirm-icon="warning"
                                                    data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-1"></i> {{ __('general.delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('users.no_users') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
