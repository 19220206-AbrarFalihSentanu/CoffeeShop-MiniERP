<?php
// File: database/migrations/2025_01_03_000002_create_inventories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0); // stok saat ini
            $table->integer('reserved')->default(0); // stok yang dipesan tapi belum dibayar
            $table->integer('available')->virtualAs('quantity - reserved'); // stok tersedia
            $table->timestamps();

            // Ensure one inventory record per product
            $table->unique('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
