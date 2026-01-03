<?php
// File: database/migrations/2026_01_03_000001_create_payments_table.php
// Jalankan: php artisan make:migration create_payments_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');

            // Payment Details
            $table->decimal('amount', 15, 2)->comment('Jumlah yang dibayar');
            $table->enum('payment_method', ['transfer_bank', 'e_wallet', 'cash'])
                ->comment('Metode pembayaran');
            $table->string('payment_proof')->nullable()
                ->comment('Path to payment proof image');
            $table->text('customer_notes')->nullable()
                ->comment('Catatan dari customer');

            // Status & Verification
            $table->enum('status', ['pending', 'verified', 'rejected'])
                ->default('pending')
                ->comment('Status verifikasi payment');
            $table->foreignId('verified_by')->nullable()
                ->constrained('users')
                ->onDelete('restrict')
                ->comment('Admin/Owner yang verifikasi');
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable()
                ->comment('Alasan jika ditolak');
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('status');
            $table->index('verified_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
