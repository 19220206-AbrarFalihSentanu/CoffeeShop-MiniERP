{{-- File: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | eUREKA Coffee</title>

    <meta name="description" content="Sistem ERP untuk Supplier Kopi" />

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
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Light Coffee Theme CSS (Brown Theme) -->
    <link rel="stylesheet" href="{{ asset('assets/css/light-coffee-theme.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Menu Sidebar -->
            @include('layouts.partials.sidebar')

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                @include('layouts.partials.navbar')

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                        {{-- Page Content --}}
                        @yield('content')

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Translations for JS -->
    <x-translations />

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- SweetAlert Global Config -->
    <script>
        // Auto-show SweetAlert for session messages
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#8B5A2B',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        @endif

        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#8B5A2B'
                });
            });
        @endif

        @if (session('warning'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#8B5A2B'
                });
            });
        @endif

        @if (session('info'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#8B5A2B'
                });
            });
        @endif

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
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
            });
        @endif

        // Coffee theme colors for SweetAlert2
        const swalCoffee = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-secondary',
                denyButton: 'btn btn-danger me-2'
            },
            buttonsStyling: false,
            confirmButtonColor: '#8B5A2B',
            iconColor: '#8B5A2B'
        });

        // Global confirmation function
        function confirmDelete(formId, title = 'Yakin ingin menghapus?', text =
            'Data yang dihapus tidak dapat dikembalikan!') {
            swalCoffee.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="bx bx-x me-1"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
            return false;
        }

        // Global confirmation for any action
        function confirmAction(formId, title, text, confirmText = 'Ya, Lanjutkan!', icon = 'question') {
            swalCoffee.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-check me-1"></i> ' + confirmText,
                cancelButtonText: '<i class="bx bx-x me-1"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
            return false;
        }

        // Toast notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Show success toast
        function showSuccess(message) {
            Toast.fire({
                icon: 'success',
                title: message
            });
        }

        // Show error toast
        function showError(message) {
            Toast.fire({
                icon: 'error',
                title: message
            });
        }

        // Show info toast
        function showInfo(message) {
            Toast.fire({
                icon: 'info',
                title: message
            });
        }

        // Show warning toast  
        function showWarning(message) {
            Toast.fire({
                icon: 'warning',
                title: message
            });
        }

        // Auto-attach SweetAlert to forms with data-confirm attribute
        document.addEventListener('DOMContentLoaded', function() {
            // Handle forms with data-confirm attribute
            document.querySelectorAll('form[data-confirm]').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const title = form.dataset.confirmTitle || 'Konfirmasi';
                    const text = form.dataset.confirm || 'Apakah Anda yakin?';
                    const icon = form.dataset.confirmIcon || 'question';
                    const confirmText = form.dataset.confirmButton || 'Ya, Lanjutkan!';
                    const cancelText = form.dataset.cancelButton || 'Batal';
                    const isDanger = form.dataset.confirmDanger === 'true';

                    swalCoffee.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        confirmButtonColor: isDanger ? '#dc3545' : '#8B5A2B',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Remove the event listener to prevent infinite loop
                            form.removeEventListener('submit', arguments.callee);
                            form.submit();
                        }
                    });
                });
            });

            // Handle buttons/links with data-confirm that trigger form submission
            document.querySelectorAll('[data-confirm-submit]').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const formId = btn.dataset.confirmSubmit;
                    const form = document.getElementById(formId);
                    if (!form) return;

                    const title = btn.dataset.confirmTitle || 'Konfirmasi';
                    const text = btn.dataset.confirm || 'Apakah Anda yakin?';
                    const icon = btn.dataset.confirmIcon || 'question';
                    const confirmText = btn.dataset.confirmButton || 'Ya, Lanjutkan!';
                    const cancelText = btn.dataset.cancelButton || 'Batal';
                    const isDanger = btn.dataset.confirmDanger === 'true';

                    swalCoffee.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        confirmButtonColor: isDanger ? '#dc3545' : '#8B5A2B',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Override native confirm() globally
            window.nativeConfirm = window.confirm;
            window.confirm = function(message) {
                // For inline onsubmit handlers, we need to handle differently
                // Return true to allow form submission for now
                return true;
            };
        });

        // Handle inline onsubmit confirms (for backward compatibility)
        function swalConfirm(message, form) {
            event.preventDefault();
            swalCoffee.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>

    @stack('scripts')
</body>

</html>
