{{-- File: resources/views/admin/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', __('menu.dashboard') . ' Admin')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 250px;
        }

        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.2);
        }

        .task-item {
            transition: background-color 0.2s ease;
            border-left: 3px solid transparent;
        }

        .task-item:hover {
            background-color: #f8f5f2;
        }

        .task-item.priority-high {
            border-left-color: #ff4c51;
        }

        .task-item.priority-medium {
            border-left-color: #ffb400;
        }

        .task-item.priority-low {
            border-left-color: #56ca00;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        {{-- Welcome Card --}}
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ __('dashboard.hello') }}, {{ Auth::user()->name }}! 👋
                            </h5>
                            <p class="mb-4">{{ __('dashboard.admin_greeting') }}</p>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('dashboard.manage_products') }}
                            </a>
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

        {{-- Quick Action Cards --}}
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-coffee"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.total_products') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['total_products'] }}</h3>
                    <a href="{{ route('admin.products.index') }}" class="small text-primary">
                        <i class="bx bx-right-arrow-alt"></i> Kelola Produk
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-credit-card"></i>
                            </span>
                        </div>
                        @if ($stats['pending_verification'] > 0)
                            <span class="badge bg-danger">{{ $stats['pending_verification'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">Verifikasi Pembayaran</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_verification'] }}</h3>
                    <a href="{{ route('admin.payments.index') }}" class="small text-info">
                        <i class="bx bx-right-arrow-alt"></i> Verifikasi Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                        @if ($stats['orders_to_process'] > 0)
                            <span class="badge bg-success">{{ $stats['orders_to_process'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">Pesanan Diproses</span>
                    <h3 class="card-title mb-2">{{ $stats['orders_to_process'] }}</h3>
                    <a href="{{ route('admin.payments.index') }}?status=verified" class="small text-success">
                        <i class="bx bx-right-arrow-alt"></i> Proses Pesanan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-car"></i>
                            </span>
                        </div>
                        @if ($stats['orders_to_ship'] > 0)
                            <span class="badge bg-warning">{{ $stats['orders_to_ship'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">Siap Kirim</span>
                    <h3 class="card-title mb-2">{{ $stats['orders_to_ship'] }}</h3>
                    <a href="{{ route('admin.payments.index') }}?status=processing" class="small text-warning">
                        <i class="bx bx-right-arrow-alt"></i> Kirim Pesanan
                    </a>
                </div>
            </div>
        </div>

        {{-- Stock Alert Cards --}}
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-error"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.low_stock_items') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['low_stock_items'] }}</h3>
                    <a href="{{ route('admin.inventory.alerts') }}" class="text-warning small">
                        <i class="bx bx-right-arrow-alt"></i> {{ __('dashboard.view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="bx bx-x-circle"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('inventory.out_of_stock') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['out_of_stock_items'] }}</h3>
                    <a href="{{ route('admin.inventory.alerts') }}" class="text-danger small">
                        <i class="bx bx-right-arrow-alt"></i> {{ __('dashboard.view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-shopping-bag"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.today_orders') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['today_orders'] }}</h3>
                    <a href="{{ route('admin.payments.index') }}" class="text-primary small">
                        <i class="bx bx-right-arrow-alt"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>

        {{-- Weekly Orders Chart --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="bx bx-bar-chart me-2"></i>Pesanan 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="weeklyOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products by Category --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="bx bx-pie-chart-alt-2 me-2"></i>Produk per Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Orders Need Attention --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-task me-2"></i>Pesanan Perlu Ditangani
                    </h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            @if ($order->payment)
                                <a href="{{ route('admin.payments.show', $order->payment) }}"
                                    class="list-group-item list-group-item-action task-item {{ $order->status == 'pending' ? 'priority-high' : ($order->status == 'paid' ? 'priority-medium' : 'priority-low') }}">
                                @else
                                    <div
                                        class="list-group-item task-item {{ $order->status == 'pending' ? 'priority-high' : ($order->status == 'paid' ? 'priority-medium' : 'priority-low') }}">
                            @endif
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $order->order_number }}</h6>
                                    <small class="text-muted">
                                        {{ $order->customer->name ?? $order->customer_name }} •
                                        {{ $order->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $order->status_badge_class }}">
                                        {{ $order->status_display }}
                                    </span>
                                    <br>
                                    <small class="fw-bold">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                                </div>
                            </div>
                            @if ($order->payment)
                                </a>
                            @else
                    </div>
                    @endif
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-check-circle bx-lg mb-2"></i>
                        <p>Semua pesanan sudah ditangani!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">
                    <i class="bx bx-error-circle me-2 text-warning"></i>Stok Menipis
                </h5>
                <a href="{{ route('admin.inventory.alerts') }}" class="btn btn-sm btn-outline-warning">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @forelse($lowStockProducts as $product)
                    <div class="d-flex align-items-center mb-3">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                class="rounded me-3" width="45" height="45" style="object-fit: cover;">
                        @else
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="bx bx-coffee"></i>
                                </span>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <small class="text-muted">Min: {{ $product->min_stock }} {{ $product->unit }}</small>
                        </div>
                        <div class="text-end">
                            @php
                                $available =
                                    ($product->inventory->quantity ?? 0) - ($product->inventory->reserved ?? 0);
                            @endphp
                            <span class="badge {{ $available <= 0 ? 'bg-danger' : 'bg-warning' }}">
                                {{ $available }} {{ $product->unit }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-check-circle bx-lg mb-2 text-success"></i>
                        <p>Semua stok aman!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Orders Chart
            const weeklyCtx = document.getElementById('weeklyOrdersChart').getContext('2d');
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($weeklyOrdersChart, 'date')) !!},
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: {!! json_encode(array_column($weeklyOrdersChart, 'orders')) !!},
                        backgroundColor: 'rgba(139, 90, 43, 0.8)',
                        borderColor: '#8B5A2B',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryData = @json($productsByCategory);

            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(c => c.name),
                    datasets: [{
                        data: categoryData.map(c => c.products_count),
                        backgroundColor: [
                            '#8B5A2B',
                            '#C9A66B',
                            '#6D4C41',
                            '#D4B896',
                            '#A67C52',
                            '#5D4037'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
@endpush


