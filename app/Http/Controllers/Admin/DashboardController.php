<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard admin
        $stats = [
            'total_products' => Product::count(),
            'low_stock_items' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock')
                    ->whereRaw('(quantity - reserved) > 0');
            })->count(),
            'out_of_stock_items' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= 0');
            })->count(),
            'pending_payments' => 0,
            'today_orders' => 0
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
