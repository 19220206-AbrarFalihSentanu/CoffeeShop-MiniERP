{{-- File: resources/views/profile/edit.blade.php --}}

@extends('layouts.app')

@section('title', __('settings.my_profile'))

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
            border: 4px solid #8B5A2B;
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.3);
        }

        .profile-photo-upload {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #8B5A2B;
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
            background: #6D4C41;
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
            color: #8B5A2B;
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
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('menu.dashboard') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('settings.my_profile') }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bx bx-user-circle me-2"></i>{{ __('settings.my_profile') }}</h4>
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
                        <label class="profile-photo-upload" title="{{ __('settings.change_photo') }}">
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
                                <div class="stat-label">{{ __('dashboard.total_orders') }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $completedOrders }}</div>
                                <div class="stat-label">{{ __('dashboard.completed') }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Delete Photo Button --}}
                    @if ($user->profile_photo)
                        <form action="{{ route('profile.delete-photo') }}" method="POST" class="mt-3"
                            data-confirm="{{ __('settings.confirm_delete_photo') }}" data-confirm-title="Hapus Foto?"
                            data-confirm-icon="warning" data-confirm-button="Ya, Hapus!" data-confirm-danger="true">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash me-1"></i>{{ __('settings.delete_photo') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Account Info --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>{{ __('settings.account_info') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('settings.joined_since') }}</small>
                        <strong>{{ $user->created_at->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('settings.last_updated') }}</small>
                        <strong>{{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">{{ __('settings.account_status') }}</small>
                        @if ($user->is_active)
                            <span class="badge bg-success">{{ __('general.active') }}</span>
                        @else
                            <span class="badge bg-danger">{{ __('general.inactive') }}</span>
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
                    <h5 class="mb-0"><i class="bx bx-edit me-2"></i>{{ __('settings.edit_profile') }}</h5>
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
                                    <label for="email" class="form-label">{{ __('auth.email') }} <span
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
                                    <label for="phone" class="form-label">{{ __('general.phone') }}</label>
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
                                    <label class="form-label">{{ __('users.role') }}</label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst($user->role->name ?? 'User') }}" disabled>
                                    <small class="text-muted">{{ __('users.role_cannot_change') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('general.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                placeholder="{{ __('orders.full_address') }}...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>{{ __('general.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bx bx-lock-alt me-2"></i>{{ __('settings.change_password') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="current_password"
                                        class="form-label">{{ __('settings.current_password') }} <span
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
                                    <label for="password" class="form-label">{{ __('settings.new_password') }} <span
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
                                    <small class="text-muted">{{ __('auth.password_min') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation"
                                        class="form-label">{{ __('settings.confirm_new_password') }} <span
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
                                <i class="bx bx-key me-1"></i>{{ __('settings.change_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone - Delete Account (Optional, only for customers) --}}
            @if ($user->isCustomer())
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bx bx-error-circle me-2"></i>{{ __('users.danger_zone') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            {{ __('users.delete_account_warning') }}
                        </p>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="bx bx-trash me-1"></i>{{ __('users.delete_my_account') }}
                        </button>
                    </div>
                </div>

                {{-- Delete Account Modal --}}
                <div class="modal fade" id="deleteAccountModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">{{ __('users.confirm_delete_account') }}</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('profile.destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <div class="alert alert-danger">
                                        <i class="bx bx-error-circle me-1"></i>
                                        <strong>{{ __('general.warning') }}!</strong>
                                        {{ __('users.action_cannot_undone') }}
                                    </div>
                                    <p>{{ __('users.enter_password_confirm') }}:</p>
                                    <div class="mb-3">
                                        <label for="delete_password" class="form-label">{{ __('auth.password') }}</label>
                                        <input type="password" class="form-control" id="delete_password" name="password"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">{{ __('general.cancel') }}</button>
                                    <button type="submit"
                                        class="btn btn-danger">{{ __('users.yes_delete_account') }}</button>
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
