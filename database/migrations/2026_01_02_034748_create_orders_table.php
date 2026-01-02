<?php
// File: database/migrations/2025_01_02_000001_create_orders_table.php
// Jalankan: php artisan make:migration create_orders_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->comment('ORD-20250102-001');

            // Customer Info
            $table->foreignId('customer_id')->constrained('users')->onDelete('restrict');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->text('shipping_address');

            // Order Details
            $table->decimal('subtotal', 15, 2)->comment('Total sebelum pajak & ongkir');
            $table->decimal('tax_rate', 5, 2)->comment('Persentase pajak (%)');
            $table->decimal('tax_amount', 15, 2)->comment('Jumlah pajak');
            $table->decimal('shipping_cost', 15, 2)->comment('Biaya ongkir');
            $table->decimal('total_amount', 15, 2)->comment('Total keseluruhan');

            // Status & Approval
            $table->enum('status', [
                'pending',      // Menunggu approval owner
                'approved',     // Disetujui owner, menunggu payment
                'rejected',     // Ditolak owner
                'paid',         // Sudah dibayar, menunggu pengiriman
                'processing',   // Sedang diproses/dikemas
                'shipped',      // Sudah dikirim
                'completed',    // Selesai
                'cancelled'     // Dibatalkan
            ])->default('pending');

            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // Payment Info
            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable()->comment('Path to payment proof image');
            $table->timestamp('paid_at')->nullable();

            // Delivery Info
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Additional Info
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('order_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('approved_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
