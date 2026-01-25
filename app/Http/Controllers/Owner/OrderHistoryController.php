<?php
// File: app/Http/Controllers/Owner/OrderHistoryController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    /**
     * Display all orders with history (all statuses)
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product', 'payment'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $orders = $query->paginate($perPage)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'completed' => Order::where('status', 'completed')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::whereIn('status', ['paid', 'processing', 'shipped'])->count(),
            'rejected' => Order::where('status', 'rejected')->count(),
        ];

        return view('owner.orders.history.index', compact('orders', 'stats'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product.inventory', 'approver', 'payment.verifier']);

        return view('owner.orders.history.show', compact('order'));
    }
}
