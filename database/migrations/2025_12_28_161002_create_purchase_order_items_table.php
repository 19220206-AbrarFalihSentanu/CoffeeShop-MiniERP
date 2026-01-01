<?php
// File: database/migrations/2025_12_29_000003_create_purchase_order_items_table.php
// Jalankan: php artisan make:migration create_purchase_order_items_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_ordered')->comment('Jumlah yang dipesan (kg)');
            $table->integer('quantity_received')->default(0)->comment('Jumlah yang sudah diterima (kg)');
            $table->decimal('unit_price', 12, 2)->comment('Harga per kg');
            $table->decimal('subtotal', 15, 2)->comment('quantity_ordered * unit_price');
            $table->text('notes')->nullable()->comment('Catatan untuk item ini');
            $table->timestamps();

            // Indexes
            $table->index('purchase_order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
