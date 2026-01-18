{{-- File: resources/views/auth/register-customer.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light-style" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Customer | {{ setting('company_name') ?? 'Eureka Kopi' }}</title>

    <!-- Favicon -->
    @if (setting('company_logo'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('company_logo')) }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #8B5A2B 0%, #6F4E37 50%, #5D4037 100%);
            min-height: 100vh;
        }

        .btn-primary {
            background-color: #8B5A2B !important;
            border-color: #8B5A2B !important;
        }

        .btn-primary:hover {
            background-color: #6F4E37 !important;
            border-color: #6F4E37 !important;
        }

        .form-control:focus {
            border-color: #8B5A2B !important;
            box-shadow: 0 0 0 0.2rem rgba(139, 90, 43, 0.25) !important;
        }

        a {
            color: #8B5A2B;
        }

        a:hover {
            color: #6F4E37;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4">
                            @if (setting('company_logo'))
                                <img src="{{ asset('storage/' . setting('company_logo')) }}"
                                    alt="{{ setting('company_name') ?? 'Logo' }}"
                                    style="width: 80px; height: 80px; object-fit: contain; border-radius: 50%; background: #f5f5f5; padding: 5px;">
                            @else
                                <span style="font-size: 60px;">☕</span>
                            @endif
                        </div>
                        <div class="text-center mb-4">
                            <span class="app-brand-text demo text-body fw-bolder fs-4" style="color: #8B5A2B;">
                                {{ setting('company_name') ?? 'Eureka Kopi' }}
                            </span>
                        </div>

                        <h4 class="mb-2 text-center" style="color: #8B5A2B;">Registrasi Customer</h4>
                        <p class="mb-4 text-center text-muted">Daftar untuk mulai berbelanja kopi terbaik</p>

                        <form action="{{ route('register.customer') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="nama@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    placeholder="Alamat lengkap" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer"
                                        onclick="togglePassword('password')"><i class="bx bx-hide"
                                            id="password-icon"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer"
                                        onclick="togglePassword('password_confirmation')"><i class="bx bx-hide"
                                            id="password_confirmation-icon"></i></span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                <i class="bx bx-user-plus me-1"></i> Daftar Sekarang
                            </button>
                        </form>

                        <p class="text-center mt-4">
                            <span class="text-muted">Sudah punya akun?</span>
                            <a href="{{ route('login') }}" class="fw-semibold">
                                Login di sini
                            </a>
                        </p>
                    </div>
                </div>

                <p class="text-center mt-4 text-white" style="opacity: 0.8;">
                    &copy; {{ date('Y') }} {{ setting('company_name') ?? 'Eureka Kopi' }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }

        // SweetAlert2 Coffee Theme
        const swalCoffee = Swal.mixin({
            confirmButtonColor: '#8B5A2B',
            cancelButtonColor: '#6c757d',
            iconColor: '#8B5A2B',
        });

        @if (session('success'))
            swalCoffee.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif

        @if (session('error'))
            swalCoffee.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
            });
        @endif

        @if ($errors->any())
            let errorList = '';
            @foreach ($errors->all() as $error)
                errorList += '• {{ $error }}\n';
            @endforeach
            swalCoffee.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<div style="text-align:left;white-space:pre-line;">' + errorList + '</div>',
            });
        @endif
    </script>
</body>

</html>


