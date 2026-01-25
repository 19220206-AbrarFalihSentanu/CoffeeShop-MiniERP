<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test email queue
echo "=================================================\n";
echo "EMAIL QUEUE TEST\n";
echo "=================================================\n\n";

echo "Queue Configuration:\n";
echo "  Driver: " . config('queue.default') . "\n";
echo "  Mail: " . config('mail.default') . "\n\n";

echo "Database Jobs:\n";
$jobCount = DB::table('jobs')->count();
echo "  Pending: " . $jobCount . "\n";

$failedCount = DB::table('failed_jobs')->count();
echo "  Failed: " . $failedCount . "\n\n";

// Queue test email
echo "Queueing test email...\n";

$order = \App\Models\Order::find(1);
$user = \App\Models\User::find(61);

if (!$order || !$user) {
    echo "ERROR: Order or User not found!\n";
    exit(1);
}

try {
    \Illuminate\Support\Facades\Mail::to($order->customer_email)->queue(new \App\Mail\OrderCreated($order, 'customer'));
    echo "✅ Customer email queued successfully!\n";

    \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\OrderCreated($order, 'admin'));
    echo "✅ Admin email queued successfully!\n\n";

    $jobCount = DB::table('jobs')->count();
    echo "Jobs in queue: " . $jobCount . "\n";

    echo "\n=================================================\n";
    echo "Processing queue jobs...\n";
    echo "=================================================\n\n";

    // Run queue:work
    passthru('php artisan queue:work --timeout=60 --tries=1 --max-jobs=10');
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
