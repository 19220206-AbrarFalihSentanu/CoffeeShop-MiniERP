<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Perubahan untuk mendukung sistem satuan berat untuk bisnis supplier kopi:
     * - Produk memiliki satuan (kg, gram, ton)
     * - Inventory menyimpan quantity dalam decimal untuk akurasi berat
     * - Order items menyimpan quantity dalam decimal
     */
    public function up(): void
    {
        // 1. Update products table - tambah kolom unit
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit', 20)->default('kg')->after('weight');
            }
            if (!Schema::hasColumn('products', 'min_order_qty')) {
                $table->decimal('min_order_qty', 12, 3)->default(1)->after('unit');
            }
            if (!Schema::hasColumn('products', 'order_increment')) {
                $table->decimal('order_increment', 12, 3)->default(1)->after('min_order_qty');
            }
        });

        // 2. Update inventories table - ubah quantity ke decimal
        // Drop virtual column first
        if (Schema::hasColumn('inventories', 'available')) {
            Schema::table('inventories', function (Blueprint $table) {
                $table->dropColumn('available');
            });
        }

        // Ubah quantity dan reserved ke decimal
        DB::statement('ALTER TABLE inventories MODIFY quantity DECIMAL(15,3) DEFAULT 0');
        DB::statement('ALTER TABLE inventories MODIFY reserved DECIMAL(15,3) DEFAULT 0');

        // 3. Update inventory_logs table
        DB::statement('ALTER TABLE inventory_logs MODIFY quantity DECIMAL(15,3)');
        DB::statement('ALTER TABLE inventory_logs MODIFY `before` DECIMAL(15,3)');
        DB::statement('ALTER TABLE inventory_logs MODIFY `after` DECIMAL(15,3)');

        // 4. Update order_items table
        DB::statement('ALTER TABLE order_items MODIFY quantity DECIMAL(12,3)');
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'unit')) {
                $table->string('unit', 20)->default('kg')->after('quantity');
            }
        });

        // 5. Update purchase_order_items table jika ada
        if (Schema::hasTable('purchase_order_items')) {
            DB::statement('ALTER TABLE purchase_order_items MODIFY quantity_ordered DECIMAL(12,3)');
            DB::statement('ALTER TABLE purchase_order_items MODIFY quantity_received DECIMAL(12,3)');
            Schema::table('purchase_order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_order_items', 'unit')) {
                    $table->string('unit', 20)->default('kg')->after('quantity_received');
                }
            });
        }

        // 6. Update carts table
        if (Schema::hasTable('carts')) {
            DB::statement('ALTER TABLE carts MODIFY quantity DECIMAL(12,3)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback purchase_order_items
        if (Schema::hasTable('purchase_order_items')) {
            DB::statement('ALTER TABLE purchase_order_items MODIFY quantity_ordered INT');
            DB::statement('ALTER TABLE purchase_order_items MODIFY quantity_received INT');
            Schema::table('purchase_order_items', function (Blueprint $table) {
                if (Schema::hasColumn('purchase_order_items', 'unit')) {
                    $table->dropColumn('unit');
                }
            });
        }

        // Rollback carts
        if (Schema::hasTable('carts')) {
            DB::statement('ALTER TABLE carts MODIFY quantity INT');
        }

        // Rollback order_items
        DB::statement('ALTER TABLE order_items MODIFY quantity INT');
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'unit')) {
                $table->dropColumn('unit');
            }
        });

        // Rollback inventory_logs
        DB::statement('ALTER TABLE inventory_logs MODIFY quantity INT');
        DB::statement('ALTER TABLE inventory_logs MODIFY `before` INT');
        DB::statement('ALTER TABLE inventory_logs MODIFY `after` INT');

        // Rollback inventories
        DB::statement('ALTER TABLE inventories MODIFY quantity INT DEFAULT 0');
        DB::statement('ALTER TABLE inventories MODIFY reserved INT DEFAULT 0');

        // Rollback products
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit', 'min_order_qty', 'order_increment']);
        });
    }
};
