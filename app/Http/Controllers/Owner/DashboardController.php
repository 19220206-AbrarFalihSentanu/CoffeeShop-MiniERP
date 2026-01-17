<?php

// File: app/Http/Controllers/Owner/DashboardController.php
// Jalankan: php artisan make:controller Owner/DashboardController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\FinancialLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // Total orders this month
        $totalOrdersThisMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $totalOrdersLastMonth = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $ordersGrowth = $totalOrdersLastMonth > 0
            ? round((($totalOrdersThisMonth - $totalOrdersLastMonth) / $totalOrdersLastMonth) * 100, 1)
            : 0;

        // Pending approvals
        $pendingApprovals = Order::where('status', 'pending')->count();

        // Monthly revenue (completed orders)
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');
        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereBetween('completed_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Total active customers
        $totalCustomers = User::whereHas('role', function ($q) {
            $q->where('name', 'customer');
        })->where('is_active', true)->count();

        // Stock alerts
        $lowStockCount = Product::whereHas('inventory', function ($q) {
            $q->whereRaw('(quantity - reserved) <= products.min_stock')
                ->whereRaw('(quantity - reserved) > 0');
        })->count();

        $outOfStockCount = Product::whereHas('inventory', function ($q) {
            $q->whereRaw('(quantity - reserved) <= 0');
        })->count();

        // Monthly order chart data (last 6 months)
        $monthlyOrdersChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthlyOrdersChart[] = [
                'month' => $month->format('M Y'),
                'orders' => Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'revenue' => Order::where('status', 'completed')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->sum('total_amount'),
            ];
        }

        // Order status distribution
        $orderStatusChart = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Top selling products
        $topProducts = Product::withSum(['orderItems as total_sold' => function ($q) {
            $q->whereHas('order', function ($oq) {
                $oq->where('status', 'completed');
            });
        }], 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['customer', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => $totalOrdersThisMonth,
            'orders_growth' => $ordersGrowth,
            'pending_approvals' => $pendingApprovals,
            'monthly_revenue' => $monthlyRevenue,
            'revenue_growth' => $revenueGrowth,
            'total_customers' => $totalCustomers,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
        ];

        return view('owner.dashboard', compact(
            'stats',
            'monthlyOrdersChart',
            'orderStatusChart',
            'topProducts',
            'recentOrders'
        ));
    }
}
