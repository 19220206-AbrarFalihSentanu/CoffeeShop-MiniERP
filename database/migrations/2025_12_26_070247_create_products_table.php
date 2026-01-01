<?php
// File: database/migrations/2025_01_03_000001_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // whole_bean, ground, instant
            $table->decimal('weight', 8, 2); // dalam gram
            $table->decimal('price', 12, 2); // harga normal

            // FITUR DISKON
            $table->boolean('has_discount')->default(false);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable(); // persentase atau nominal
            $table->decimal('discount_value', 8, 2)->nullable(); // nilai diskon
            $table->date('discount_start_date')->nullable();
            $table->date('discount_end_date')->nullable();

            // Media
            $table->string('image')->nullable();
            $table->json('images')->nullable(); // untuk multiple images

            // Stock Management
            $table->integer('min_stock')->default(10); // minimum stock alert

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // produk unggulan

            $table->timestamps();
            $table->softDeletes(); // soft delete untuk keep history
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
