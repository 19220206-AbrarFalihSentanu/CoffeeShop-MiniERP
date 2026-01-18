<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        // My orders count
        $myOrders = Order::where('customer_id', $user->id)->count();

        // Pending orders (pending approval, approved waiting payment)
        $pendingOrders = Order::where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        // In progress orders (paid, processing, shipped)
        $inProgressOrders = Order::where('customer_id', $user->id)
            ->whereIn('status', ['paid', 'processing', 'shipped'])
            ->count();

        // Completed orders
        $completedOrders = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Total spent (completed orders)
        $totalSpent = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // This month spent
        $thisMonthSpent = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$startOfMonth, $now])
            ->sum('total_amount');

        // Monthly spending chart (last 6 months)
        $monthlySpendingChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthlySpendingChart[] = [
                'month' => $month->format('M Y'),
                'spent' => Order::where('customer_id', $user->id)
                    ->where('status', 'completed')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->sum('total_amount'),
                'orders' => Order::where('customer_id', $user->id)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Recent orders
        $recentOrders = Order::where('customer_id', $user->id)
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // Order status distribution
        $orderStatusChart = Order::where('customer_id', $user->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Frequently purchased products
        $frequentProducts = Product::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('order', function ($oq) use ($user) {
                $oq->where('customer_id', $user->id)
                    ->where('status', 'completed');
            });
        })
            ->withSum(['orderItems as total_purchased' => function ($q) use ($user) {
                $q->whereHas('order', function ($oq) use ($user) {
                    $oq->where('customer_id', $user->id)
                        ->where('status', 'completed');
                });
            }], 'quantity')
            ->orderByDesc('total_purchased')
            ->take(5)
            ->get();

        // Featured products (for recommendations)
        $featuredProducts = Product::where('is_active', true)
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) > 0');
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        $stats = [
            'my_orders' => $myOrders,
            'pending_orders' => $pendingOrders,
            'in_progress_orders' => $inProgressOrders,
            'completed_orders' => $completedOrders,
            'total_spent' => $totalSpent,
            'this_month_spent' => $thisMonthSpent,
        ];

        return view('customer.dashboard', compact(
            'stats',
            'monthlySpendingChart',
            'recentOrders',
            'orderStatusChart',
            'frequentProducts',
            'featuredProducts'
        ));
    }
}

