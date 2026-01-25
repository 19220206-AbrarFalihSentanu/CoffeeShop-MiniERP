<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low {--notify : Send email notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products with low stock and optionally send notification';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for low stock products...');

        // Get products with inventory where stock is below minimum
        $lowStockProducts = Product::with(['category', 'inventory'])
            ->whereHas('inventory', function ($query) {
                // Get products where inventory quantity is less than or equal to product min_stock
                $query->whereRaw('inventories.quantity <= COALESCE(products.min_stock, 10)');
            })
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                // Add stock value from inventory for easy access
                $product->stock = $product->inventory->quantity ?? 0;
                return $product;
            })
            ->sortBy('stock')
            ->values();

        if ($lowStockProducts->isEmpty()) {
            $this->info('✅ All products have sufficient stock.');
            return Command::SUCCESS;
        }

        // Display table of low stock products
        $this->warn("⚠️ Found {$lowStockProducts->count()} products with low stock:");

        $tableData = $lowStockProducts->map(function ($product) {
            return [
                'ID' => $product->id,
                'Name' => $product->name,
                'Category' => $product->category->name ?? '-',
                'Stock' => $product->stock,
                'Min Stock' => $product->min_stock ?? 10,
                'Status' => $product->stock <= 5 ? '🚨 CRITICAL' : '⚠️ LOW',
            ];
        })->toArray();

        $this->table(
            ['ID', 'Name', 'Category', 'Stock', 'Min Stock', 'Status'],
            $tableData
        );

        // Send notification if flag is set
        if ($this->option('notify')) {
            $this->sendNotification($lowStockProducts);
        }

        return Command::SUCCESS;
    }

    /**
     * Send low stock notification to admin users.
     */
    protected function sendNotification($products): void
    {
        $this->info('📧 Sending notification to admin users...');

        // Get admin/owner users to notify
        $adminUsers = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['owner', 'admin']);
        })->get();

        if ($adminUsers->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return;
        }

        foreach ($adminUsers as $user) {
            try {
                Mail::to($user->email)->queue(new LowStockAlert($products));
                $this->info("✅ Notification queued for: {$user->email}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to queue notification for {$user->email}: {$e->getMessage()}");
            }
        }

        $this->info('📬 All notifications have been queued.');
    }
}
