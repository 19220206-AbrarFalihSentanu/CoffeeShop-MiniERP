<?php
// File: database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Arabica',
                'slug' => 'arabica',
                'description' => 'Kopi Arabica premium dengan cita rasa smooth dan aroma harum',
                'icon' => 'bx-coffee',
                'is_active' => true
            ],
            [
                'name' => 'Robusta',
                'slug' => 'robusta',
                'description' => 'Kopi Robusta dengan kandungan kafein tinggi dan rasa yang kuat',
                'icon' => 'bx-coffee-togo',
                'is_active' => true
            ],
            [
                'name' => 'Liberica',
                'slug' => 'liberica',
                'description' => 'Kopi Liberica dengan aroma unik dan rasa yang berbeda',
                'icon' => 'bx-coffee-bean',
                'is_active' => true
            ],
            [
                'name' => 'Campuran',
                'slug' => 'campuran',
                'description' => 'Blend kopi dengan kombinasi berbagai jenis biji',
                'icon' => 'bx-shuffle',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories berhasil di-seed!');
    }
}
