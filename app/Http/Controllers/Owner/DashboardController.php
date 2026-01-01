<?php

// File: app/Http/Controllers/Owner/DashboardController.php
// Jalankan: php artisan make:controller Owner/DashboardController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard owner
        $stats = [
            'total_orders' => 0, // Akan diisi nanti
            'pending_approvals' => 0,
            'monthly_revenue' => 0,
            'total_customers' => 0,

            // Stock info
            'low_stock_count' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock')
                    ->whereRaw('(quantity - reserved) > 0');
            })->count(),

            'out_of_stock_count' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= 0');
            })->count(),
        ];

        return view('owner.dashboard', compact('stats'));
    }
}
