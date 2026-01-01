<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard customer
        $stats = [
            'my_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'total_spent' => 0
        ];

        return view('customer.dashboard', compact('stats'));
    }
}
