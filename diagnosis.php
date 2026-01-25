<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=" . str_repeat("=", 70) . "\n";
echo "EMAIL SYSTEM DIAGNOSIS\n";
echo "=" . str_repeat("=", 70) . "\n\n";

// 1. Check mail configuration
echo "1️⃣ MAIL CONFIGURATION\n";
echo "─" . str_repeat("─", 70) . "\n";
echo "Mail Mailer: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Mail From Address: " . config('mail.from.address') . "\n";
echo "Queue Connection: " . config('queue.default') . "\n\n";

// 2. Check queue jobs
echo "2️⃣ QUEUE STATUS\n";
echo "─" . str_repeat("─", 70) . "\n";
$pendingJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();
echo "Pending jobs: " . $pendingJobs . "\n";
echo "Failed jobs: " . $failedJobs . "\n\n";

// 3. Check orders
echo "3️⃣ ORDERS\n";
echo "─" . str_repeat("─", 70) . "\n";
$totalOrders = \App\Models\Order::count();
echo "Total orders: " . $totalOrders . "\n";

if ($totalOrders > 0) {
    $lastOrder = \App\Models\Order::latest()->first();
    echo "\nLast order details:\n";
    echo "  Order Number: " . $lastOrder->order_number . "\n";
    echo "  Customer Email: " . $lastOrder->customer_email . "\n";
    echo "  Status: " . $lastOrder->status . "\n";
    echo "  Created: " . $lastOrder->created_at . "\n";
} else {
    echo "❌ No orders found yet\n";
}

echo "\n";

// 4. Check admin/owner users
echo "4️⃣ ADMIN/OWNER USERS\n";
echo "─" . str_repeat("─", 70) . "\n";
$admins = \App\Models\User::whereHas('role', function ($q) {
    $q->where('name', 'owner')->orWhere('name', 'admin');
})->get();

if ($admins->count() > 0) {
    foreach ($admins as $admin) {
        echo "  • " . $admin->name . " (" . $admin->email . ") - Role: " . $admin->role->name . "\n";
    }
} else {
    echo "❌ No admin/owner users found\n";
}

echo "\n";

// 5. Check role table
echo "5️⃣ ROLES IN DATABASE\n";
echo "─" . str_repeat("─", 70) . "\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    echo "  • " . $role->name . " (ID: " . $role->id . ") - " . $role->display_name . "\n";
}

echo "\n";

// 6. Test the admin query used in CheckoutController
echo "6️⃣ CHECKOUT CONTROLLER ADMIN QUERY TEST\n";
echo "─" . str_repeat("─", 70) . "\n";
echo "Testing: whereIn('slug', ['owner', 'admin'])\n";
$result1 = \App\Models\User::whereHas('role', function ($query) {
    $query->whereIn('slug', ['owner', 'admin']);
})->get();
echo "  Result: " . $result1->count() . " users found (WRONG - 'slug' doesn't exist)\n\n";

echo "Testing: whereIn('name', ['owner', 'admin'])\n";
$result2 = \App\Models\User::whereHas('role', function ($query) {
    $query->whereIn('name', ['owner', 'admin']);
})->get();
echo "  Result: " . $result2->count() . " users found (CORRECT)\n";
if ($result2->count() > 0) {
    foreach ($result2 as $user) {
        echo "    - " . $user->name . " (" . $user->email . ")\n";
    }
}

echo "\n";

// 7. Summary
echo "7️⃣ DIAGNOSIS SUMMARY\n";
echo "─" . str_repeat("─", 70) . "\n";

$issues = [];

if (config('mail.default') === 'log') {
    $issues[] = "❌ MAIL_MAILER=log - Email is being saved to logs, not sent!";
}

if ($pendingJobs > 0) {
    $issues[] = "⚠️ Queue has " . $pendingJobs . " pending jobs - queue worker not running";
}

if ($admins->count() === 0) {
    $issues[] = "❌ No admin/owner users exist in database";
}

if (empty($issues)) {
    echo "✅ All checks passed!\n";
} else {
    echo "ISSUES FOUND:\n";
    foreach ($issues as $issue) {
        echo "  " . $issue . "\n";
    }
}

echo "\n" . str_repeat("=", 72) . "\n";
