<?php
// File: database/migrations/2025_12_27_000001_create_carts_table.php
// Jalankan: php artisan make:migration create_carts_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2); // Price saat ditambahkan ke cart
            $table->timestamps();

            // Unique constraint: satu user hanya bisa punya 1 item product di cart
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
