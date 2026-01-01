<?php
// File: database/migrations/2025_12_29_000005_update_inventory_logs_for_po.php
// Jalankan: php artisan make:migration update_inventory_logs_for_po

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            // Tambah kolom source untuk tracking asal stock movement
            $table->string('source')->after('reference')->nullable()->comment('purchase_order, sales_order, manual, adjustment');
            $table->unsignedBigInteger('source_id')->after('source')->nullable()->comment('ID dari source');

            // Add index
            $table->index(['source', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropIndex(['source', 'source_id']);
            $table->dropColumn(['source', 'source_id']);
        });
    }
};
