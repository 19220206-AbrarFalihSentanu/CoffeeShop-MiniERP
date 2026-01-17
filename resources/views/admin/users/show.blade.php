{{-- File: resources/views/admin/users/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail User</h5>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <div class="avatar avatar-xl mb-3">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <span
                                class="badge 
                            @if ($user->role->name == 'owner') bg-primary
                            @elseif($user->role->name == 'admin') bg-info
                            @else bg-secondary @endif">
                                {{ $user->role->display_name }}
                            </span>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} ms-2">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Pribadi</h6>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Nama Lengkap</label>
                                <p class="mb-0">{{ $user->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <p class="mb-0">
                                    <i class="bx bx-envelope me-1"></i>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">No. Telepon</label>
                                <p class="mb-0">
                                    @if ($user->phone)
                                        <i class="bx bx-phone me-1"></i>
                                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Alamat</label>
                                <p class="mb-0">
                                    @if ($user->address)
                                        <i class="bx bx-map me-1"></i>
                                        {{ $user->address }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Akun</h6>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Role</label>
                                <p class="mb-0">
                                    <span
                                        class="badge 
                                    @if ($user->role->name == 'owner') bg-primary
                                    @elseif($user->role->name == 'admin') bg-info
                                    @else bg-secondary @endif">
                                        {{ $user->role->display_name }}
                                    </span>
                                </p>
                                <small class="text-muted">{{ $user->role->description }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Status Akun</label>
                                <p class="mb-0">
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Email Verified</label>
                                <p class="mb-0">
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="bx bx-check-circle"></i> Terverifikasi
                                        </span>
                                        <br>
                                        <small
                                            class="text-muted">{{ $user->email_verified_at->format('d M Y H:i') }}</small>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bx bx-time"></i> Belum Terverifikasi
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Terdaftar Sejak</label>
                                <p class="mb-0">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ $user->created_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Terakhir Diupdate</label>
                                <p class="mb-0">
                                    <i class="bx bx-time me-1"></i>
                                    {{ $user->updated_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                                        <i class="bx {{ $user->is_active ? 'bx-block' : 'bx-check' }}"></i>
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                    data-confirm="Data user akan dihapus permanen!" data-confirm-title="Hapus User?"
                                    data-confirm-icon="warning" data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash"></i> Hapus User
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">
                                    <i class="bx bx-info-circle"></i> Ini adalah akun Anda sendiri
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
