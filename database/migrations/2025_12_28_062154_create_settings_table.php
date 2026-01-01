<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'company_name', 'tax_rate', dll
            $table->text('value')->nullable(); // nilai setting
            $table->string('type')->default('text'); // text, number, image, textarea, boolean
            $table->string('group')->default('general'); // general, landing_page, system
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
