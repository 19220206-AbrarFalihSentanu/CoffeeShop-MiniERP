{{-- File: resources/views/owner/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Anda login sebagai <span class="fw-bold">Owner</span>. Kelola bisnis kopi Anda dengan bijak.
                            </p>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Lihat Laporan</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-shopping-bag rounded bx-md text-primary"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Order</span>
                    <h3 class="card-title mb-2">{{ $stats['total_orders'] }}</h3>
                    <small class="text-muted">Bulan ini</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-time-five rounded bx-md text-warning"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pending Approval</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_approvals'] }}</h3>
                    <small class="text-warning">Perlu disetujui</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-dollar-circle rounded bx-md text-success"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Revenue Bulan Ini</span>
                    <h3 class="card-title mb-2">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h3>
                    <small class="text-success">+12% dari bulan lalu</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="bx bx-user rounded bx-md text-info"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Customer</span>
                    <h3 class="card-title mb-2">{{ $stats['total_customers'] }}</h3>
                    <small class="text-muted">Customer aktif</small>
                </div>
            </div>
        </div>

        <!-- Stock Alert Cards -->
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-error bx-md text-warning"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Low Stock Items</span>
                    <h3 class="card-title mb-2">{{ $stats['low_stock_count'] ?? 0 }}</h3>
                    <a href="{{ route('owner.inventory.alerts') }}" class="text-warning small">
                        <i class="bx bx-right-arrow-alt"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-x-circle bx-md text-danger"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Out of Stock</span>
                    <h3 class="card-title mb-2">{{ $stats['out_of_stock_count'] ?? 0 }}</h3>
                    <a href="{{ route('owner.inventory.alerts') }}" class="text-danger small">
                        <i class="bx bx-right-arrow-alt"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- End of File: resources/views/owner/dashboard.blade.php --}}
