{{-- File: resources/views/owner/financial/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-2">
                    <span class="text-muted fw-light">Financial /</span> Dashboard
                </h4>
                <p class="text-muted mb-0">
                    Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </p>
            </div>
            <div>
                <a href="{{ route('owner.financial.index') }}" class="btn btn-label-primary me-2">
                    <i class='bx bx-list-ul me-1'></i> View All Logs
                </a>
                <a href="{{ route('owner.financial.expense.create') }}" class="btn btn-primary">
                    <i class='bx bx-plus me-1'></i> Add Expense
                </a>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.financial.dashboard') }}" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class='bx bx-search me-1'></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Total Income</h5>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class='bx bx-trending-up bx-sm'></i>
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-2 text-success">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                        <small class="text-muted">Period: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Total Expense</h5>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class='bx bx-trending-down bx-sm'></i>
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-2 text-danger">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</h3>
                        <small class="text-muted">Period: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Net Profit</h5>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <span
                                    class="avatar-initial rounded bg-label-{{ $stats['net_profit'] >= 0 ? 'success' : 'danger' }}">
                                    <i class='bx bx-wallet bx-sm'></i>
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-2 text-{{ $stats['net_profit'] >= 0 ? 'success' : 'danger' }}">
                            Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}
                        </h3>
                        <small class="text-muted">Income - Expense</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-title mb-0">
                                <h5 class="mb-0">Total Orders</h5>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class='bx bx-cart bx-sm'></i>
                                </span>
                            </div>
                        </div>
                        <h3 class="mb-2">{{ $stats['total_orders'] }}</h3>
                        <small class="text-muted">Approved orders</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Monthly Trend Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Monthly Trend (Last 6 Months)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyTrendChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Income by Category -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Income by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense by Category & Top Products -->
        <div class="row mb-4">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Expense by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseCategoryChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top 5 Best Selling Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th class="text-end">Quantity Sold</th>
                                        <th class="text-end">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td class="text-end">{{ $product->total_quantity }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
                    datasets: [{
                            label: 'Income',
                            data: {!! json_encode(array_column($monthlyData, 'income')) !!},
                            borderColor: '#28c76f',
                            backgroundColor: 'rgba(40, 199, 111, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Expense',
                            data: {!! json_encode(array_column($monthlyData, 'expense')) !!},
                            borderColor: '#ea5455',
                            backgroundColor: 'rgba(234, 84, 85, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Profit',
                            data: {!! json_encode(array_column($monthlyData, 'profit')) !!},
                            borderColor: '#696cff',
                            backgroundColor: 'rgba(105, 108, 255, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Income Category Chart
            const incomeCtx = document.getElementById('incomeCategoryChart').getContext('2d');
            new Chart(incomeCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($incomeByCategory->pluck('category')->toArray()) !!},
                    datasets: [{
                        data: {!! json_encode($incomeByCategory->pluck('total')->toArray()) !!},
                        backgroundColor: ['#696cff', '#28c76f', '#00cfe8', '#ff9f43', '#ea5455']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Expense Category Chart
            const expenseCtx = document.getElementById('expenseCategoryChart').getContext('2d');
            new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expenseByCategory->pluck('category')->toArray()) !!},
                    datasets: [{
                        data: {!! json_encode($expenseByCategory->pluck('total')->toArray()) !!},
                        backgroundColor: ['#ea5455', '#ff9f43', '#00cfe8', '#28c76f', '#696cff']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
