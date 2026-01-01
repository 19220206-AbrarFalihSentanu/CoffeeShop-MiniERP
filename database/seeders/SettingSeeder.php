<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'company_name',
                'value' => 'Eureka Kopi',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama perusahaan'
            ],
            [
                'key' => 'company_email',
                'value' => 'info@eurekakopi.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Email perusahaan'
            ],
            [
                'key' => 'company_phone',
                'value' => '081234567890',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nomor telepon perusahaan'
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Kopi No. 1, Jakarta Selatan, DKI Jakarta 12345',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Alamat lengkap perusahaan'
            ],
            [
                'key' => 'company_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'description' => 'Logo perusahaan'
            ],

            // System Settings
            [
                'key' => 'tax_rate',
                'value' => '11',
                'type' => 'number',
                'group' => 'system',
                'description' => 'Tarif pajak PPN dalam persen (%)'
            ],
            [
                'key' => 'shipping_cost',
                'value' => '25000',
                'type' => 'number',
                'group' => 'system',
                'description' => 'Biaya ongkos kirim standar (Rp)'
            ],
            [
                'key' => 'min_order_amount',
                'value' => '100000',
                'type' => 'number',
                'group' => 'system',
                'description' => 'Minimal jumlah order (Rp)'
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Mata uang'
            ],

            // Landing Page Settings
            [
                'key' => 'landing_hero_title',
                'value' => 'Supplier Kopi Terbaik Indonesia',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Judul hero section'
            ],
            [
                'key' => 'landing_hero_subtitle',
                'value' => 'Kopi berkualitas premium langsung dari petani pilihan ke tangan Anda',
                'type' => 'textarea',
                'group' => 'landing_page',
                'description' => 'Subjudul hero section'
            ],
            [
                'key' => 'landing_hero_image',
                'value' => null,
                'type' => 'image',
                'group' => 'landing_page',
                'description' => 'Gambar hero section'
            ],
            [
                'key' => 'landing_about_title',
                'value' => 'Tentang Eureka Kopi',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Judul about section'
            ],
            [
                'key' => 'landing_about_content',
                'value' => 'Eureka Kopi adalah supplier kopi premium yang berkomitmen menyediakan biji kopi berkualitas tinggi dari berbagai daerah di Indonesia. Kami bekerja sama langsung dengan petani lokal untuk menghadirkan kopi terbaik untuk bisnis Anda.',
                'type' => 'textarea',
                'group' => 'landing_page',
                'description' => 'Konten about section'
            ],
            [
                'key' => 'landing_about_image',
                'value' => null,
                'type' => 'image',
                'group' => 'landing_page',
                'description' => 'Gambar about section'
            ],

            // Social Media
            [
                'key' => 'landing_whatsapp',
                'value' => '6281234567890',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Nomor WhatsApp (format: 62xxx)'
            ],
            [
                'key' => 'landing_instagram',
                'value' => '@eurekakopi',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Username Instagram'
            ],
            [
                'key' => 'landing_facebook',
                'value' => 'EurekaKopi',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Username Facebook'
            ],
            [
                'key' => 'landing_email',
                'value' => 'contact@eurekakopi.com',
                'type' => 'text',
                'group' => 'landing_page',
                'description' => 'Email kontak landing page'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Settings berhasil di-seed!');
    }
}
