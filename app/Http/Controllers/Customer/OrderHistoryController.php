<?php
// File: app/Http/Controllers/Customer/OrderHistoryController.php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    /**
     * Display customer's order history (all statuses)
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'payment'])
            ->forCustomer(auth()->id())
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

        // Search by order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage)->withQueryString();

        // Get statistics for this customer
        $stats = [
            'total' => Order::forCustomer(auth()->id())->count(),
            'completed' => Order::forCustomer(auth()->id())->where('status', 'completed')->count(),
            'pending' => Order::forCustomer(auth()->id())->where('status', 'pending')->count(),
            'processing' => Order::forCustomer(auth()->id())->whereIn('status', ['approved', 'paid', 'processing', 'shipped'])->count(),
        ];

        return view('customer.orders.history.index', compact('orders', 'stats'));
    }
}
