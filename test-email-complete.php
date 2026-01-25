<?php

/**
 * Complete Email System Test
 * Tests: Mail configuration, queue system, and email delivery
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Mail\OrderCreated;
use App\Models\Order;

echo "═══════════════════════════════════════════════════════════════════════\n";
echo "📧 COMPLETE EMAIL SYSTEM TEST\n";
echo "═══════════════════════════════════════════════════════════════════════\n\n";

// Test 1: Mail Configuration
echo "1️⃣  MAIL CONFIGURATION\n";
echo "───────────────────────────────────────────────────────────────────────\n";
$mailer = config('mail.default');
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');
$username = config('mail.mailers.smtp.username');
$encryption = config('mail.mailers.smtp.encryption');

echo "Mailer: " . $mailer . "\n";
echo "Host: " . $host . "\n";
echo "Port: " . $port . "\n";
echo "Username: " . $username . "\n";
echo "Encryption: " . $encryption . "\n\n";

if ($mailer === 'smtp') {
    echo "✅ Mail configuration is CORRECT (smtp)\n\n";
} else {
    echo "❌ ERROR: Mail configuration is set to '$mailer' (should be 'smtp')\n\n";
}

// Test 2: Queue Configuration
echo "2️⃣  QUEUE CONFIGURATION\n";
echo "───────────────────────────────────────────────────────────────────────\n";
$queueConnection = config('queue.default');
echo "Queue Connection: " . $queueConnection . "\n";

if ($queueConnection === 'database') {
    echo "✅ Queue is configured to use database\n\n";
} else {
    echo "⚠️  Queue is configured to use '$queueConnection'\n\n";
}

// Test 3: Admin/Owner Users
echo "3️⃣  ADMIN/OWNER USERS\n";
echo "───────────────────────────────────────────────────────────────────────\n";
try {
    $adminUsers = User::whereHas('role', function ($q) {
        $q->whereIn('name', ['owner', 'admin']);
    })->get();

    echo "Found " . count($adminUsers) . " admin/owner users:\n";
    foreach ($adminUsers as $user) {
        echo "  • {$user->name} ({$user->email}) - Role: {$user->role->name}\n";
    }

    if (count($adminUsers) > 0) {
        echo "✅ Admin users query working CORRECTLY\n\n";
    } else {
        echo "❌ No admin users found\n\n";
    }
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Test 4: Orders
echo "4️⃣  ORDERS IN SYSTEM\n";
echo "───────────────────────────────────────────────────────────────────────\n";
$ordersCount = Order::count();
echo "Total orders: " . $ordersCount . "\n";

if ($ordersCount > 0) {
    $lastOrder = Order::latest('created_at')->first();
    echo "\nLast Order:\n";
    echo "  Order Number: {$lastOrder->order_number}\n";
    echo "  Customer Email: {$lastOrder->customer_email}\n";
    echo "  Status: {$lastOrder->status}\n";
    echo "  Created: {$lastOrder->created_at}\n";
    echo "\n✅ Orders exist in system\n\n";
} else {
    echo "⚠️  No orders found in system\n\n";
}

// Test 5: Queue Status
echo "5️⃣  QUEUE STATUS\n";
echo "───────────────────────────────────────────────────────────────────────\n";
$pendingJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();

echo "Pending jobs: " . $pendingJobs . "\n";
echo "Failed jobs: " . $failedJobs . "\n\n";

if ($failedJobs > 0) {
    echo "⚠️  " . $failedJobs . " failed job(s) detected\n";
    echo "   Run: php artisan queue:flush to clear failed jobs\n\n";
}

// Test 6: Email Queue Dispatch Test
echo "6️⃣  EMAIL QUEUE DISPATCH TEST\n";
echo "───────────────────────────────────────────────────────────────────────\n";

if ($ordersCount > 0) {
    try {
        $order = Order::first();

        // Queue email to customer
        Mail::to($order->customer_email)->queue(
            new OrderCreated($order, 'customer')
        );

        // Queue email to admin
        $adminEmails = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['owner', 'admin']);
        })->pluck('email')->toArray();

        foreach ($adminEmails as $adminEmail) {
            Mail::to($adminEmail)->queue(
                new OrderCreated($order, 'admin')
            );
        }

        $jobsCount = DB::table('jobs')->count();
        echo "✅ Emails queued successfully!\n";
        echo "   Total jobs in queue: " . $jobsCount . "\n";
        echo "   Queued for customer: {$order->customer_email}\n";
        echo "   Queued for " . count($adminEmails) . " admin/owner users\n\n";
    } catch (\Exception $e) {
        echo "❌ ERROR queuing emails: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "⚠️  Cannot test email dispatch - no orders in system\n\n";
}

// Test 7: Summary
echo "7️⃣  SUMMARY\n";
echo "───────────────────────────────────────────────────────────────────────\n";

$issues = [];

if ($mailer !== 'smtp') {
    $issues[] = "Mail configuration not set to SMTP";
}

if (count($adminUsers) === 0) {
    $issues[] = "No admin/owner users found";
}

if ($failedJobs > 0) {
    $issues[] = "Failed jobs exist in queue";
}

if (count($issues) === 0) {
    echo "✅ ALL TESTS PASSED!\n\n";
    echo "The email system is configured correctly.\n";
    echo "Emails will be sent to admin/owner users when customers create orders.\n\n";
    echo "📌 IMPORTANT:\n";
    echo "   To actually PROCESS the queued emails, run the queue worker:\n";
    echo "   php artisan queue:work --timeout=60 --tries=1 --max-jobs=10\n\n";
    echo "   On Windows, queue:work may not work in background.\n";
    echo "   See run-queue-worker.ps1 or setup-queue-worker.sh for solutions.\n";
} else {
    echo "❌ ISSUES FOUND:\n";
    foreach ($issues as $i => $issue) {
        echo "   " . ($i + 1) . ". " . $issue . "\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════════════\n";
