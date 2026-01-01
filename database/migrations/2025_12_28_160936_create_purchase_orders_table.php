<?php
// File: database/migrations/2025_12_29_000002_create_purchase_orders_table.php
// Jalankan: php artisan make:migration create_purchase_orders_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique()->comment('PO-20250101-001');
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict')->comment('Admin yang membuat');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict')->comment('Owner yang approve');
            $table->decimal('subtotal', 15, 2)->default(0)->comment('Total sebelum pajak');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('Jumlah pajak');
            $table->decimal('total_amount', 15, 2)->default(0)->comment('Total setelah pajak');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('draft');
            $table->date('expected_delivery_date')->comment('Tanggal pengiriman diharapkan');
            $table->text('notes')->nullable()->comment('Catatan untuk owner/supplier');
            $table->text('rejection_reason')->nullable()->comment('Alasan jika ditolak');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu submit untuk approval');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable()->comment('Waktu barang diterima lengkap');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('created_by');
            $table->index('approved_by');
            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
