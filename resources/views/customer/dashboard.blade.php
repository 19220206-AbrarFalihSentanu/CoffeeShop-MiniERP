{{-- File: resources/views/customer/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', __('dashboard.customer_title'))

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ __('dashboard.hello') }}, {{ Auth::user()->name }}! ☕</h5>
                            <p class="mb-4">
                                {{ __('dashboard.customer_welcome') }} <span class="fw-bold">Mini ERP Kopi</span>.
                                {{ __('dashboard.customer_subtitle') }}
                            </p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-store me-1"></i>{{ __('dashboard.view_catalog') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" height="140"
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
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.my_orders') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['my_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1 text-warning">{{ __('dashboard.pending') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1 text-success">{{ __('dashboard.completed') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['completed_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.total_spent') }}</span>
                    <h3 class="card-title text-nowrap mb-2">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
