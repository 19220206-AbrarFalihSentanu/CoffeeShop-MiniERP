{{-- File: resources/views/owner/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', __('dashboard.owner_title'))

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.2);
        }

        .growth-positive {
            color: #28c76f;
        }

        .growth-negative {
            color: #ea5455;
        }

        .recent-order-item {
            transition: background-color 0.2s ease;
        }

        .recent-order-item:hover {
            background-color: #f8f5f2;
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
                            <h5 class="card-title text-primary">{{ __('dashboard.welcome') }}, {{ Auth::user()->name }}! 🎉
                            </h5>
                            <p class="mb-4">
                                {{ __('dashboard.logged_as') }} <span class="fw-bold">Owner</span>.
                                {{ __('dashboard.owner_subtitle') }}
                            </p>
                            <a href="{{ route('owner.reports.financial') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('dashboard.view_report') }}
                            </a>
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

        {{-- Main Stats Cards --}}
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.total_orders') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['total_orders'] }}</h3>
                    <small class="{{ $stats['orders_growth'] >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        <i class="bx {{ $stats['orders_growth'] >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                        {{ abs($stats['orders_growth']) }}% {{ __('dashboard.this_month') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time-five"></i>
                            </span>
                        </div>
                        @if ($stats['pending_approvals'] > 0)
                            <span class="badge bg-warning">{{ $stats['pending_approvals'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.pending_approvals') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_approvals'] }}</h3>
                    <a href="{{ route('owner.orders.approval.index') }}" class="text-warning small">
                        <i class="bx bx-right-arrow-alt"></i> {{ __('dashboard.needs_approval') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-dollar-circle"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.monthly_revenue') }}</span>
                    <h3 class="card-title mb-2">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h3>
                    <small class="{{ $stats['revenue_growth'] >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        <i class="bx {{ $stats['revenue_growth'] >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                        {{ abs($stats['revenue_growth']) }}% {{ __('dashboard.from_last_month') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.total_customers') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['total_customers'] }}</h3>
                    <small class="text-muted">{{ __('dashboard.active_customers') }}</small>
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
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.low_stock') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['low_stock_count'] }}</h3>
                    <a href="{{ route('owner.inventory.alerts') }}" class="text-warning small">
                        <i class="bx bx-right-arrow-alt"></i> {{ __('general.view_detail') }}
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
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.out_of_stock') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['out_of_stock_count'] }}</h3>
                    <a href="{{ route('owner.inventory.alerts') }}" class="text-danger small">
                        <i class="bx bx-right-arrow-alt"></i> {{ __('general.view_detail') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Revenue & Orders Chart --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-line-chart me-2"></i>Trend Pesanan & Pendapatan (6 Bulan Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Status Distribution --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="bx bx-pie-chart-alt-2 me-2"></i>Status Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-trophy me-2"></i>Produk Terlaris
                    </h5>
                    <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $index => $product)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded bg-label-primary">{{ $index + 1 }}</span>
                            </div>
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-coffee"></i>
                                    </span>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->category->name ?? '-' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-label-success">{{ $product->total_sold ?? 0 }} terjual</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-package bx-lg mb-2"></i>
                            <p>Belum ada data penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-receipt me-2"></i>Pesanan Terbaru
                    </h5>
                    <a href="{{ route('owner.orders.approval.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr class="recent-order-item">
                                        <td>
                                            <a href="{{ route('owner.orders.approval.show', $order) }}">
                                                {{ $order->order_number }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $order->customer->name ?? $order->customer_name }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $order->status_badge_class }}">
                                                {{ $order->status_display }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Belum ada pesanan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue & Orders Chart
            const revenueCtx = document.getElementById('revenueOrdersChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($monthlyOrdersChart, 'month')) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode(array_column($monthlyOrdersChart, 'revenue')) !!},
                        backgroundColor: 'rgba(139, 90, 43, 0.8)',
                        borderColor: '#8B5A2B',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }, {
                        label: 'Jumlah Pesanan',
                        data: {!! json_encode(array_column($monthlyOrdersChart, 'orders')) !!},
                        type: 'line',
                        borderColor: '#C9A66B',
                        backgroundColor: 'rgba(201, 166, 107, 0.2)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const statusData = @json($orderStatusChart);
            const statusLabels = {
                'pending': 'Menunggu',
                'approved': 'Disetujui',
                'rejected': 'Ditolak',
                'paid': 'Dibayar',
                'processing': 'Diproses',
                'shipped': 'Dikirim',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            const statusColors = {
                'pending': '#ffb400',
                'approved': '#16b1ff',
                'rejected': '#ff4c51',
                'paid': '#56ca00',
                'processing': '#8B5A2B',
                'shipped': '#00cfe8',
                'completed': '#28c76f',
                'cancelled': '#6c757d'
            };

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData).map(k => statusLabels[k] || k),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: Object.keys(statusData).map(k => statusColors[k] ||
                            '#999'),
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
