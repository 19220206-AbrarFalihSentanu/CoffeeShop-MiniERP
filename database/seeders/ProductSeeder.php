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
            // Arabica Products
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Gayo Premium',
                'description' => 'Kopi Arabica dari dataran tinggi Gayo, Aceh dengan cita rasa fruity dan floral yang khas',
                'type' => 'whole_bean',
                'weight' => 250,
                'cost_price' => 45000,
                'price' => 85000,
                'has_discount' => true,
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(7),
                'min_stock' => 10,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 50
            ],
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Toraja',
                'description' => 'Kopi Arabica Toraja dengan body yang balance dan aftertaste yang panjang',
                'type' => 'ground',
                'weight' => 250,
                'cost_price' => 52000,
                'price' => 95000,
                'has_discount' => false,
                'min_stock' => 10,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 30
            ],
            [
                'category_id' => $arabica->id,
                'name' => 'Arabica Kintamani',
                'description' => 'Kopi Arabica dari Bali dengan aroma citrus yang segar',
                'type' => 'whole_bean',
                'weight' => 250,
                'cost_price' => 42000,
                'price' => 78000,
                'has_discount' => true,
                'discount_type' => 'fixed',
                'discount_value' => 10000,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(14),
                'min_stock' => 10,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 45
            ],

            // Robusta Products
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Lampung',
                'description' => 'Kopi Robusta Lampung dengan rasa yang kuat dan kandungan kafein tinggi',
                'type' => 'ground',
                'weight' => 250,
                'cost_price' => 28000,
                'price' => 55000,
                'has_discount' => false,
                'min_stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 60
            ],
            [
                'category_id' => $robusta->id,
                'name' => 'Robusta Bengkulu Premium',
                'description' => 'Robusta grade 1 dari Bengkulu dengan body yang thick',
                'type' => 'whole_bean',
                'weight' => 250,
                'cost_price' => 33000,
                'price' => 62000,
                'has_discount' => true,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(10),
                'min_stock' => 15,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 40
            ],

            // Blend Products
            [
                'category_id' => $blend->id,
                'name' => 'House Blend Special',
                'description' => 'Campuran Arabica dan Robusta dengan komposisi 60:40 untuk rasa yang balance',
                'type' => 'ground',
                'weight' => 250,
                'cost_price' => 38000,
                'price' => 68000,
                'has_discount' => false,
                'min_stock' => 20,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 70
            ],
            [
                'category_id' => $blend->id,
                'name' => 'Premium Blend',
                'description' => 'Blend premium dari berbagai origin terbaik',
                'type' => 'whole_bean',
                'weight' => 250,
                'cost_price' => 48000,
                'price' => 88000,
                'has_discount' => true,
                'discount_type' => 'fixed',
                'discount_value' => 15000,
                'discount_start_date' => now(),
                'discount_end_date' => now()->addDays(5),
                'min_stock' => 10,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 25
            ],
            [
                'category_id' => $blend->id,
                'name' => 'Instant Coffee Mix',
                'description' => 'Kopi instant praktis dengan rasa yang tetap premium',
                'type' => 'instant',
                'weight' => 100,
                'cost_price' => 18000,
                'price' => 35000,
                'has_discount' => false,
                'min_stock' => 30,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 100
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
