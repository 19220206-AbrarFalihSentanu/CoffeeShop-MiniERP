<?php
// File: database/migrations/2025_12_29_000004_create_financial_logs_table.php
// Jalankan: php artisan make:migration create_financial_logs_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']); // income = penjualan, expense = purchase
            $table->string('category'); // 'purchase', 'sales', 'operational', dll
            $table->decimal('amount', 15, 2);
            $table->string('reference_type')->nullable(); // PurchaseOrder, Order, dll
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->date('transaction_date');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_logs');
    }
};
