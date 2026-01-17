{{-- File: resources/views/customer/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', __('dashboard.customer_title'))

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

        .order-timeline {
            position: relative;
            padding-left: 30px;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #8B5A2B;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #8B5A2B33;
        }

        .product-card-mini {
            transition: all 0.3s ease;
        }

        .product-card-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(139, 90, 43, 0.15);
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
                            <h5 class="card-title text-primary">{{ __('dashboard.hello') }}, {{ Auth::user()->name }}! ☕</h5>
                            <p class="mb-4">
                                {{ __('dashboard.customer_welcome') }} <span class="fw-bold">Eureka Kopi</span>.
                                {{ __('dashboard.customer_subtitle') }}
                            </p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-store me-1"></i>{{ __('dashboard.view_catalog') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                alt="Welcome" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Stats Cards --}}
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-receipt"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.my_orders') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['my_orders'] }}</h3>
                    <a href="{{ route('customer.orders.index') }}" class="small text-primary">
                        <i class="bx bx-right-arrow-alt"></i> Lihat Semua
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
                                <i class="bx bx-time-five"></i>
                            </span>
                        </div>
                        @if ($stats['pending_orders'] > 0)
                            <span class="badge bg-warning">{{ $stats['pending_orders'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.pending') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['pending_orders'] }}</h3>
                    <small class="text-muted">Menunggu konfirmasi/pembayaran</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                        @if ($stats['in_progress_orders'] > 0)
                            <span class="badge bg-info">{{ $stats['in_progress_orders'] }}</span>
                        @endif
                    </div>
                    <span class="fw-semibold d-block mb-1">Dalam Proses</span>
                    <h3 class="card-title mb-2">{{ $stats['in_progress_orders'] }}</h3>
                    <small class="text-muted">Sedang diproses/dikirim</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="avatar flex-shrink-0 mb-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-check-circle"></i>
                        </span>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ __('dashboard.completed') }}</span>
                    <h3 class="card-title mb-2">{{ $stats['completed_orders'] }}</h3>
                    <small class="text-success">Pesanan selesai</small>
                </div>
            </div>
        </div>

        {{-- Spending Stats --}}
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-wallet bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">{{ __('dashboard.total_spent') }}</span>
                            <h3 class="card-title mb-0">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-calendar bx-sm"></i>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Belanja Bulan Ini</span>
                            <h3 class="card-title mb-0">Rp {{ number_format($stats['this_month_spent'], 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Spending Chart --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="bx bx-line-chart me-2"></i>Riwayat Belanja (6 Bulan Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="spendingChart"></canvas>
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

        {{-- Recent Orders --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-history me-2"></i>Pesanan Terbaru
                    </h5>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="order-timeline">
                        @forelse($recentOrders as $order)
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a href="{{ route('customer.orders.show', $order) }}" class="fw-semibold">
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="text-muted small mb-1">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </p>
                                        <p class="mb-0 small">
                                            {{ $order->items->count() }} produk •
                                            <span class="fw-bold">Rp
                                                {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <span class="badge {{ $order->status_badge_class }}">
                                        {{ $order->status_display }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bx bx-shopping-bag bx-lg mb-2"></i>
                                <p>Belum ada pesanan</p>
                                <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-primary">
                                    Mulai Belanja
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Frequently Purchased --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">
                        <i class="bx bx-heart me-2"></i>Produk Favorit Anda
                    </h5>
                    <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-primary">
                        Belanja Lagi
                    </a>
                </div>
                <div class="card-body">
                    @forelse($frequentProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-coffee"></i>
                                    </span>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}/{{ $product->unit }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-label-primary">{{ $product->total_purchased ?? 0 }}x dibeli</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-package bx-lg mb-2"></i>
                            <p>Belum ada riwayat pembelian</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recommended Products --}}
        @if ($featuredProducts->count() > 0)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">
                            <i class="bx bx-star me-2"></i>Rekomendasi Untuk Anda
                        </h5>
                        <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Katalog
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($featuredProducts as $product)
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="card product-card-mini h-100">
                                        <div class="position-relative">
                                            @if ($product->image)
                                                <img src="{{ Storage::url($product->image) }}" class="card-img-top"
                                                    alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="height: 150px;">
                                                    <i class="bx bx-coffee bx-lg text-muted"></i>
                                                </div>
                                            @endif
                                            @if ($product->discount_percent > 0)
                                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                                                    -{{ $product->discount_percent }}%
                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                            <p class="card-text mb-2">
                                                @if ($product->discount_percent > 0)
                                                    <small class="text-muted text-decoration-line-through">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </small>
                                                    <br>
                                                @endif
                                                <span class="fw-bold text-primary">
                                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                                </span>
                                                <small class="text-muted">/{{ $product->unit }}</small>
                                            </p>
                                            <a href="{{ route('catalog.show', $product) }}"
                                                class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bx bx-cart-add me-1"></i> Pesan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Spending Chart
            const spendingCtx = document.getElementById('spendingChart').getContext('2d');
            new Chart(spendingCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($monthlySpendingChart, 'month')) !!},
                    datasets: [{
                        label: 'Pengeluaran (Rp)',
                        data: {!! json_encode(array_column($monthlySpendingChart, 'spent')) !!},
                        borderColor: '#8B5A2B',
                        backgroundColor: 'rgba(139, 90, 43, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Jumlah Pesanan',
                        data: {!! json_encode(array_column($monthlySpendingChart, 'orders')) !!},
                        borderColor: '#C9A66B',
                        backgroundColor: 'rgba(201, 166, 107, 0.1)',
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
                            },
                            ticks: {
                                stepSize: 1
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

            if (Object.keys(statusData).length > 0) {
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
            }
        });
    </script>
@endpush
