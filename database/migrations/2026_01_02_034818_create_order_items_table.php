<?php
// File: database/migrations/2025_01_02_000002_create_order_items_table.php
// Jalankan: php artisan make:migration create_order_items_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');

            // Product Snapshot (saat order dibuat)
            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('product_weight', 8, 2);
            $table->string('product_type');

            // Pricing
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2)->comment('Harga per unit saat order');
            $table->decimal('subtotal', 15, 2)->comment('quantity * unit_price');

            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
