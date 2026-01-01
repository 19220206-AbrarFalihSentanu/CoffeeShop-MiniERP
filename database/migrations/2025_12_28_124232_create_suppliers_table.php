<?php
// File: database/migrations/2025_12_29_000001_create_suppliers_table.php
// Jalankan: php artisan make:migration create_suppliers_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // SUP-001
            $table->string('name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->enum('type', ['petani', 'distributor', 'koperasi'])->default('petani');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
