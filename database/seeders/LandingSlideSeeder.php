<?php

namespace Database\Seeders;

use App\Models\LandingSlide;
use Illuminate\Database\Seeder;

class LandingSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Supplier Kopi Terbaik Indonesia',
                'subtitle' => 'Kopi berkualitas premium langsung dari petani pilihan ke tangan Anda',
                'image' => null,
                'button_text' => 'Lihat Produk',
                'button_link' => '#produk',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Kualitas Premium Harga Terjangkau',
                'subtitle' => 'Dapatkan biji kopi terbaik dengan harga kompetitif untuk bisnis Anda',
                'image' => null,
                'button_text' => 'Hubungi Kami',
                'button_link' => '#kontak',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Dari Petani Langsung ke Anda',
                'subtitle' => 'Kami bekerja sama langsung dengan petani kopi lokal untuk menghadirkan cita rasa autentik',
                'image' => null,
                'button_text' => 'Tentang Kami',
                'button_link' => '#tentang',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Pengiriman Cepat & Aman',
                'subtitle' => 'Layanan pengiriman terpercaya ke seluruh Indonesia',
                'image' => null,
                'button_text' => 'Pesan Sekarang',
                'button_link' => '/login',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Partner Terpercaya Bisnis Kopi',
                'subtitle' => 'Dipercaya oleh ratusan cafe dan restoran di seluruh Indonesia',
                'image' => null,
                'button_text' => 'Lihat Partner',
                'button_link' => '#partner',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            LandingSlide::updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }

        $this->command->info('✅ Landing slides berhasil di-seed!');
    }
}
