<?php
// File: database/migrations/2025_01_03_000003_create_inventory_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siapa yang melakukan perubahan
            $table->enum('type', ['in', 'out', 'adjustment']); // tipe transaksi
            $table->integer('quantity'); // jumlah perubahan (+ atau -)
            $table->integer('before'); // stok sebelum
            $table->integer('after'); // stok sesudah
            $table->text('notes')->nullable(); // catatan
            $table->string('reference')->nullable(); // reference number (PO number, SO number, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
