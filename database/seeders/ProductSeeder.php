<?php
// File: database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $arabica = Category::where('slug', 'arabica')->first();
        $robusta = Category::where('slug', 'robusta')->first();
        $blend = Category::where('slug', 'campuran')->first();

        $products = [
            // Arabica Products - Satuan kg untuk supplier
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Gayo Premium',
                'description' => 'Kopi Arabica dari dataran tinggi Gayo, Aceh dengan cita rasa fruity dan floral yang khas. Grade specialty dengan moisture 11-12%.',
                'type' => 'whole_bean',
                'weight' => 1000, // 1kg per unit
                'unit' => 'kg',
                'min_order_qty' => 5, // Minimal order 5 kg
                'order_increment' => 0.5, // Kelipatan 0.5 kg
                'cost_price' => 85000, // Harga per kg
                'price' => 125000, // Harga jual per kg
                'has_discount' => true,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(14),
                'min_stock' => 25,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 150.5 // 150.5 kg
            ],
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Toraja Sapan',
                'description' => 'Kopi Arabica Toraja dari Sapan dengan body yang balance, aftertaste panjang, dan aroma chocolate. Diproses dengan metode wet-hulled.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 5,
                'order_increment' => 0.5,
                'cost_price' => 95000,
                'price' => 140000,
                'has_discount' => false,
                'min_stock' => 20,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 85
            ],
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Kintamani Bali',
                'description' => 'Kopi Arabica dari Kintamani, Bali dengan aroma citrus yang segar dan keasaman yang bright. Natural process.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 5,
                'order_increment' => 0.5,
                'cost_price' => 82000,
                'price' => 120000,
                'has_discount' => true,
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(21),
                'min_stock' => 20,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 120
            ],
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Flores Bajawa',
                'description' => 'Kopi Arabica dari Flores dengan profil rasa nutty dan sweet tobacco. Semi-washed process.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 5,
                'order_increment' => 0.5,
                'cost_price' => 78000,
                'price' => 115000,
                'has_discount' => false,
                'min_stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 65.5
            ],

            // Robusta Products
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Lampung Grade 1',
                'description' => 'Kopi Robusta Lampung grade 1 dengan rasa yang kuat, kandungan kafein tinggi, dan body yang thick. Cocok untuk espresso blend.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 10, // Robusta biasanya order lebih banyak
                'order_increment' => 1,
                'cost_price' => 45000,
                'price' => 72000,
                'has_discount' => false,
                'min_stock' => 50,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 250
            ],
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Bengkulu Premium',
                'description' => 'Robusta grade 1 dari Bengkulu dengan body yang thick dan aftertaste cokelat. Low acidity.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 10,
                'order_increment' => 1,
                'cost_price' => 48000,
                'price' => 78000,
                'has_discount' => true,
                'discount_type' => 'percentage',
                'discount_value' => 8,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(10),
                'min_stock' => 40,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 180
            ],
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Java Dampit',
                'description' => 'Robusta dari Dampit, Malang dengan karakter earthy dan woody. Fermented untuk mengurangi keasaman.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 10,
                'order_increment' => 1,
                'cost_price' => 42000,
                'price' => 68000,
                'has_discount' => false,
                'min_stock' => 40,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 200
            ],

            // Blend Products
            [
                'category_id' => $blend->id,
                'name' => 'House Blend Espresso',
                'description' => 'Campuran Arabica Gayo dan Robusta Lampung (60:40) untuk espresso dengan crema yang tebal dan rasa yang balance.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 5,
                'order_increment' => 0.5,
                'cost_price' => 58000,
                'price' => 92000,
                'has_discount' => false,
                'min_stock' => 30,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 175
            ],
            [
                'category_id' => $blend->id,
                'name' => 'Premium Blend Filter',
                'description' => 'Blend premium untuk manual brew (70% Arabica multi-origin, 30% washed Robusta). Sweet dan clean cup.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 5,
                'order_increment' => 0.5,
                'cost_price' => 72000,
                'price' => 110000,
                'has_discount' => true,
                'discount_type' => 'fixed',
                'discount_value' => 8000,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(7),
                'min_stock' => 20,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 95
            ],

            // Ground Coffee (Sudah digiling)
            [
                'category_id' => $blend->id,
                'name' => 'House Blend Ground - Medium',
                'description' => 'House Blend yang sudah digiling medium untuk drip dan pour over. Fresh roast weekly.',
                'type' => 'ground',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 2,
                'order_increment' => 0.5,
                'cost_price' => 62000,
                'price' => 98000,
                'has_discount' => false,
                'min_stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 45.5
            ],

            // Green Bean (belum roast)
            [
                'category_id' => $arabica->id,
                'name' => 'Green Bean Arabica Gayo',
                'description' => 'Green bean Arabica Gayo untuk roaster. Grade 1, screen 16-18, moisture max 12%.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 25,
                'order_increment' => 5,
                'cost_price' => 65000,
                'price' => 95000,
                'has_discount' => false,
                'min_stock' => 100,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 500
            ],

            // Bulk Robusta untuk industri (dalam kg, order besar)
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Lampung Industrial',
                'description' => 'Robusta Lampung untuk pembelian industri dalam jumlah besar. Grade 4, cocok untuk RTD dan instant coffee.',
                'type' => 'whole_bean',
                'weight' => 1000,
                'unit' => 'kg',
                'min_order_qty' => 100, // Min order 100 kg
                'order_increment' => 25, // Kelipatan 25 kg
                'cost_price' => 32000,
                'price' => 45000,
                'has_discount' => true,
                'discount_type' => 'percentage',
                'discount_value' => 5,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(30),
                'min_stock' => 500,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 2500 // 2.5 ton dalam kg
            ],
        ];

        foreach ($products as $productData) {
            $stock = $productData['stock'];
            unset($productData['stock']);

            $product = Product::create($productData);

            // Buat inventory untuk produk
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => $stock,
                'reserved' => 0
            ]);
        }

        $this->command->info('✅ Products dan inventory berhasil di-seed!');
        $this->command->info('📦 Total produk: ' . Product::count());
    }
}
