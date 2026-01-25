<?php

namespace App\Console\Commands;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-queue {--user-id=1} {--order-id=1}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Test email queue functionality by sending a test order email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $orderId = $this->option('order-id');

        $this->info('🚀 Starting Email Queue Test...');
        $this->info("Queue Connection: " . config('queue.default'));
        $this->info("Queue Driver: " . config('queue.connections.' . config('queue.default') . '.driver'));

        // Get test order
        $order = Order::find($orderId);
        if (!$order) {
            $this->error("❌ Order dengan ID {$orderId} tidak ditemukan!");
            return 1;
        }

        // Get test user
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ User dengan ID {$userId} tidak ditemukan!");
            return 1;
        }

        $this->info("📧 Order Found: {$order->order_number}");
        $this->info("📧 Customer Email: {$order->customer_email}");
        $this->info("📧 User Email: {$user->email}");

        try {
            // Queue email ke customer
            $this->info("\n⏳ Queueing email to customer...");
            Mail::to($order->customer_email)->queue(new OrderCreated($order, 'customer'));
            $this->info("✅ Customer email queued successfully!");

            // Queue email ke admin
            $this->info("⏳ Queueing email to admin...");
            Mail::to($user->email)->queue(new OrderCreated($order, 'admin'));
            $this->info("✅ Admin email queued successfully!");

            $this->info("\n" . str_repeat('=', 60));
            $this->info("✅ EMAIL QUEUE TEST SUCCESSFUL!");
            $this->info(str_repeat('=', 60));
            $this->info("\n📝 NEXT STEPS:");
            $this->info("1. Start the queue worker: php artisan queue:work");
            $this->info("2. Monitor queue jobs in the background");
            $this->info("3. Check 'jobs' table for pending/completed jobs");
            $this->info("4. Check email logs in storage/logs for actual email sending");
            $this->info("\nFor production hosting:");
            $this->info("- Configure a process manager (Supervisor) to keep queue:work running");
            $this->info("- Or use queue:listen for development");
            $this->info(str_repeat('=', 60));

            return 0;
        } catch (\Exception $e) {
            $this->error("\n❌ Email Queue Test FAILED!");
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
