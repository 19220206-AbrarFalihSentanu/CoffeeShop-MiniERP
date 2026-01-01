<?php
// File: database/seeders/SupplierSeeder.php
// Jalankan: php artisan make:seeder SupplierSeeder

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP-001',
                'name' => 'Koperasi Gayo Arabica',
                'contact_person' => 'Pak Usman',
                'email' => 'koperasi.gayo@example.com',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Kopi Gayo No. 15',
                'city' => 'Takengon',
                'province' => 'Aceh',
                'postal_code' => '24500',
                'type' => 'koperasi',
                'notes' => 'Supplier utama Arabica Gayo, kualitas premium',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-002',
                'name' => 'Petani Toraja Bersatu',
                'contact_person' => 'Ibu Maria',
                'email' => 'petani.toraja@example.com',
                'phone' => '0813-2345-6789',
                'address' => 'Desa Lempo, Kec. Sesean',
                'city' => 'Toraja Utara',
                'province' => 'Sulawesi Selatan',
                'postal_code' => '91831',
                'type' => 'petani',
                'notes' => 'Kelompok petani Arabica Toraja, hasil organik',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-003',
                'name' => 'CV Robusta Lampung Jaya',
                'contact_person' => 'Pak Budi',
                'email' => 'robusta.lampung@example.com',
                'phone' => '0821-3456-7890',
                'address' => 'Jl. Raya Liwa No. 88',
                'city' => 'Lampung Barat',
                'province' => 'Lampung',
                'postal_code' => '34814',
                'type' => 'distributor',
                'notes' => 'Distributor Robusta grade 1 dan 2',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-004',
                'name' => 'Koperasi Kintamani Bali',
                'contact_person' => 'Pak Made',
                'email' => 'kintamani.bali@example.com',
                'phone' => '0819-3456-7890',
                'address' => 'Jl. Raya Penelokan',
                'city' => 'Bangli',
                'province' => 'Bali',
                'postal_code' => '80652',
                'type' => 'koperasi',
                'notes' => 'Arabica Kintamani dengan citrus notes',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-005',
                'name' => 'Petani Bengkulu Sejahtera',
                'contact_person' => 'Pak Ahmad',
                'email' => 'bengkulu.sejahtera@example.com',
                'phone' => '0817-2345-6789',
                'address' => 'Desa Seberang Musi',
                'city' => 'Bengkulu Tengah',
                'province' => 'Bengkulu',
                'postal_code' => '38319',
                'type' => 'petani',
                'notes' => 'Robusta Bengkulu premium quality',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        $this->command->info('✅ ' . count($suppliers) . ' suppliers berhasil di-seed!');
    }
}
