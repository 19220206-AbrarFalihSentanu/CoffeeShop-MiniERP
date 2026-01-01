{{-- File: resources/views/admin/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Halo, {{ Auth::user()->name }}! 👋</h5>
                            <p class="mb-4">
                                Selamat bekerja sebagai <span class="fw-bold">Admin</span>. Kelola operasional harian dengan
                                efisien.
                            </p>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Kelola Produk</a>
                        </div> 
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" height="140"
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
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-coffee bx-md text-primary"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Produk</span>
                    <h3 class="card-title mb-2">{{ $stats['total_products'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-error bx-md text-danger"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Stok Menipis</span>
                    <h3 class="card-title mb-2">{{ $stats['low_stock_items'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-credit-card bx-md text-warning"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pending Payment</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_payments'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <i class="bx bx-shopping-bag bx-md text-success"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">Order Hari Ini</span>
                    <h3 class="card-title mb-2">{{ $stats['today_orders'] }}</h3>
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
                    <h3 class="card-title mb-2">{{ $stats['low_stock_items'] }}</h3>
                    <a href="{{ route('admin.inventory.alerts') }}" class="text-warning small">
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
                    <h3 class="card-title mb-2">{{ $stats['out_of_stock_items'] ?? 0 }}</h3>
                    <a href="{{ route('admin.inventory.alerts') }}" class="text-danger small">
                        <i class="bx bx-right-arrow-alt"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
