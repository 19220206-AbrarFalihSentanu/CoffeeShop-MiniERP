<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
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

        // Basic stats
        $totalProducts = Product::count();

        // Stock alerts
        $lowStockItems = Product::whereHas('inventory', function ($q) {
            $q->whereRaw('(quantity - reserved) <= products.min_stock')
                ->whereRaw('(quantity - reserved) > 0');
        })->count();

        $outOfStockItems = Product::whereHas('inventory', function ($q) {
            $q->whereRaw('(quantity - reserved) <= 0');
        })->count();

        // Pending payments (orders approved but not yet paid)
        $pendingPayments = Order::where('status', 'approved')->count();

        // Payments waiting for verification
        $pendingVerification = Payment::where('status', 'pending')->count();

        // Today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Orders to process (paid orders)
        $ordersToProcess = Order::where('status', 'paid')->count();

        // Orders to ship (processing)
        $ordersToShip = Order::where('status', 'processing')->count();

        // Weekly order chart data (last 7 days)
        $weeklyOrdersChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $weeklyOrdersChart[] = [
                'date' => $date->format('D, d M'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // Products by category chart
        $productsByCategory = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        // Recent orders that need attention (pending, approved, paid, processing)
        $recentOrders = Order::with(['customer', 'payment'])
            ->whereIn('status', ['pending', 'approved', 'paid', 'processing'])
            ->latest()
            ->take(5)
            ->get();

        // Low stock products list
        $lowStockProducts = Product::with('inventory')
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock');
            })
            ->take(5)
            ->get();

        // Order status summary for today
        $todayOrderStatus = Order::whereDate('created_at', today())
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        $stats = [
            'total_products' => $totalProducts,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems,
            'pending_payments' => $pendingPayments,
            'pending_verification' => $pendingVerification,
            'today_orders' => $todayOrders,
            'orders_to_process' => $ordersToProcess,
            'orders_to_ship' => $ordersToShip,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'weeklyOrdersChart',
            'productsByCategory',
            'recentOrders',
            'lowStockProducts',
            'todayOrderStatus'
        ));
    }
}

