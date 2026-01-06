{{-- File: resources/views/profile/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
    <style>
        .profile-photo-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #696cff;
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.3);
        }

        .profile-photo-upload {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #696cff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid white;
        }

        .profile-photo-upload:hover {
            background: #5f61e6;
            transform: scale(1.1);
        }

        .profile-photo-upload input {
            display: none;
        }

        .profile-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
            padding: 1rem 0;
            border-top: 1px solid #eee;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #696cff;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #697a8d;
        }
    </style>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profil Saya</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-user-circle me-2"></i>Profil Saya</h4>
    </div>

    <div class="row">
        {{-- Left Column - Photo & Info --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center pt-4">
                    {{-- Profile Photo --}}
                    <div class="profile-photo-container mb-3">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-photo"
                            id="profilePhotoPreview">
                        <label class="profile-photo-upload" title="Ganti Foto">
                            <i class="bx bx-camera"></i>
                            <input type="file" id="profilePhotoInput" accept="image/*">
                        </label>
                    </div>

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <span class="badge bg-label-primary mb-2">
                        {{ ucfirst($user->role->name ?? 'User') }}
                    </span>
                    <p class="text-muted mb-0">
                        <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                    </p>
                    @if ($user->phone)
                        <p class="text-muted mb-0">
                            <i class="bx bx-phone me-1"></i>{{ $user->phone }}
                        </p>
                    @endif

                    {{-- Quick Stats --}}
                    @if ($user->isCustomer())
                        @php
                            $orderCount = \App\Models\Order::where('customer_id', $user->id)->count();
                            $completedOrders = \App\Models\Order::where('customer_id', $user->id)
                                ->where('status', 'completed')
                                ->count();
                        @endphp
                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-value">{{ $orderCount }}</div>
                                <div class="stat-label">Total Order</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $completedOrders }}</div>
                                <div class="stat-label">Selesai</div>
                            </div>
                        </div>
                    @endif

                    {{-- Delete Photo Button --}}
                    @if ($user->profile_photo)
                        <form action="{{ route('profile.delete-photo') }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin ingin menghapus foto profil?')">
                                <i class="bx bx-trash me-1"></i>Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Account Info --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>Info Akun</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Bergabung Sejak</small>
                        <strong>{{ $user->created_at->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Terakhir Update</small>
                        <strong>{{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Status Akun</small>
                        @if ($user->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Edit Forms --}}
        <div class="col-lg-8">
            {{-- Update Profile Form --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-edit me-2"></i>Edit Profil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                        id="profileForm">
                        @csrf
                        @method('PATCH')

                        {{-- Hidden file input for photo --}}
                        <input type="file" name="profile_photo" id="profilePhotoHidden" style="display: none"
                            accept="image/*">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst($user->role->name ?? 'User') }}" disabled>
                                    <small class="text-muted">Role tidak dapat diubah</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-lock-alt me-2"></i>Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="current_password">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="password">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="password_confirmation">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-key me-1"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone - Delete Account (Optional, only for customers) --}}
            @if ($user->isCustomer())
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bx bx-error-circle me-2"></i>Zona Berbahaya</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen.
                            Sebelum menghapus akun, pastikan untuk mengunduh data yang ingin Anda simpan.
                        </p>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="bx bx-trash me-1"></i>Hapus Akun Saya
                        </button>
                    </div>
                </div>

                {{-- Delete Account Modal --}}
                <div class="modal fade" id="deleteAccountModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('profile.destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <div class="alert alert-danger">
                                        <i class="bx bx-error-circle me-1"></i>
                                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                                    </div>
                                    <p>Untuk mengonfirmasi, masukkan password Anda:</p>
                                    <div class="mb-3">
                                        <label for="delete_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="delete_password" name="password"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun Saya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Profile photo preview & upload
        document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePhotoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);

                // Copy to hidden input
                const hiddenInput = document.getElementById('profilePhotoHidden');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                hiddenInput.files = dataTransfer.files;
            }
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });
    </script>
@endpush
