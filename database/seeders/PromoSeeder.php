<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'title' => 'Diskon Awal Tahun',
                'description' => 'Dapatkan diskon spesial 20% untuk semua produk kopi premium kami. Promo terbatas!',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'promo_code' => 'NEWYEAR2026',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Beli 3 Gratis 1',
                'description' => 'Promo spesial! Beli 3 pack kopi arabika, gratis 1 pack kopi robusta pilihan.',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'promo_code' => 'BELI3GRATIS1',
                'start_date' => now(),
                'end_date' => now()->addDays(45),
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Ongkir',
                'description' => 'Gratis ongkos kirim untuk pembelian minimal Rp 500.000 ke seluruh Indonesia.',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'promo_code' => 'FREEONGKIR',
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Member Exclusive',
                'description' => 'Khusus member setia, dapatkan potongan harga 15% untuk setiap transaksi.',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'promo_code' => 'MEMBER15',
                'start_date' => now(),
                'end_date' => now()->addDays(90),
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
