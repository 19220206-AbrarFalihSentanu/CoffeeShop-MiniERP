<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('company_name') ?? config('app.name', 'Eureka Kopi') }}</title>

    <!-- Favicon -->
    @if (setting('company_logo'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('company_logo')) }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Soft Blue Coffee Theme Styles */
        .bg-coffee-gradient {
            background: linear-gradient(135deg, #8B5A2B 0%, #6F4E37 50%, #5D4037 100%);
        }

        .text-coffee {
            color: #8B5A2B;
        }

        .border-coffee {
            border-color: #8B5A2B;
        }

        .bg-coffee {
            background-color: #8B5A2B;
        }

        .hover\:bg-coffee-dark:hover {
            background-color: #6F4E37;
        }

        .focus\:ring-coffee:focus {
            --tw-ring-color: #8B5A2B;
        }

        .focus\:border-coffee:focus {
            border-color: #8B5A2B;
        }

        /* Custom checkbox and input focus */
        input[type="checkbox"]:checked {
            background-color: #8B5A2B;
            border-color: #8B5A2B;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #8B5A2B !important;
            --tw-ring-color: rgba(111, 143, 191, 0.5) !important;
        }

        /* Password input with eye icon inside */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 2.5rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        .password-toggle:hover {
            color: #8B5A2B;
        }

        /* Responsive Card Layout */
        .auth-card {
            display: flex;
            flex-direction: column;
        }

        .auth-brand {
            width: 100%;
            min-height: auto;
            padding: 1.5rem;
        }

        .auth-form {
            width: 100%;
            padding: 1.5rem;
        }

        .brand-features {
            display: none;
        }

        @media (min-width: 768px) {
            .auth-card {
                flex-direction: row;
            }

            .auth-brand {
                width: 40%;
                min-height: 500px;
                padding: 2rem;
            }

            .auth-form {
                width: 60%;
                padding: 2rem;
            }

            .brand-features {
                display: block;
            }
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 bg-coffee-gradient">
        <!-- Main Card Container - Responsive Layout -->
        <div class="w-full max-w-4xl bg-white shadow-2xl overflow-hidden rounded-2xl auth-card">

            <!-- Left Side - Logo & Branding -->
            <div class="auth-brand"
                style="background: linear-gradient(135deg, #8B5A2B 0%, #6F4E37 50%, #5D4037 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white;">
                <a href="/" class="flex flex-col items-center text-center"
                    style="text-decoration: none; color: white;">
                    @if (setting('company_logo'))
                        <div
                            style="width: 100px; height: 100px; border-radius: 50%; background: white; padding: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); margin-bottom: 0.75rem;">
                            <img src="{{ asset('storage/' . setting('company_logo')) }}"
                                alt="{{ setting('company_name') ?? 'Logo' }}"
                                style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                        </div>
                    @else
                        <div
                            style="width: 100px; height: 100px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); margin-bottom: 0.75rem;">
                            <span style="font-size: 50px;">☕</span>
                        </div>
                    @endif
                    <h1 style="font-size: 1.25rem; font-weight: bold; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        {{ setting('company_name') ?? 'Eureka Kopi' }}
                    </h1>
                </a>
                <p style="margin-top: 0.5rem; opacity: 0.8; text-align: center; font-size: 0.75rem;">
                    Sistem Manajemen Toko Kopi Terpadu
                </p>
                <div class="brand-features" style="margin-top: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; opacity: 0.7; font-size: 0.875rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kelola inventory dengan mudah</span>
                    </div>
                    <div
                        style="display: flex; align-items: center; gap: 0.5rem; opacity: 0.7; font-size: 0.875rem; margin-top: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Pantau penjualan real-time</span>
                    </div>
                    <div
                        style="display: flex; align-items: center; gap: 0.5rem; opacity: 0.7; font-size: 0.875rem; margin-top: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Laporan keuangan otomatis</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-form" style="display: flex; flex-direction: column; justify-content: center;">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-sm text-white/80">
            &copy; {{ date('Y') }} {{ setting('company_name') ?? 'Eureka Kopi' }}. All rights reserved.
        </p>
    </div>

    <script>
        // Auto-show SweetAlert for session messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B5A2B',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        @if ($errors->any())
            let errorList = '';
            @foreach ($errors->all() as $error)
                errorList += '• {{ $error }}\n';
            @endforeach
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<div style="text-align:left;white-space:pre-line;">' + errorList + '</div>',
                confirmButtonColor: '#8B5A2B'
            });
        @endif

        // SweetAlert2 Soft Blue Coffee Theme Configuration
        const swalCoffee = Swal.mixin({
            confirmButtonColor: '#8B5A2B',
            cancelButtonColor: '#6c757d',
            iconColor: '#8B5A2B',
        });

        // Toast notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            iconColor: '#8B5A2B',
        });

        // Helper functions
        function showSuccess(message) {
            swalCoffee.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
            });
        }

        function showError(message) {
            swalCoffee.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
            });
        }

        function showWarning(message) {
            swalCoffee.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: message,
            });
        }
    </script>
</body>

</html>

