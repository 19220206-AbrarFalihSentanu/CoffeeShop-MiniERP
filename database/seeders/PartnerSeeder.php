<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Starbucks Indonesia',
                'logo' => null,
                'website' => 'https://www.starbucks.co.id',
                'description' => 'Partner retail kopi terbesar',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Kopi Kenangan',
                'logo' => null,
                'website' => 'https://kopikenangan.com',
                'description' => 'Partner lokal kopi kekinian',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Janji Jiwa',
                'logo' => null,
                'website' => 'https://janjijiwa.com',
                'description' => 'Partner franchise kopi',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Fore Coffee',
                'logo' => null,
                'website' => 'https://fore.coffee',
                'description' => 'Partner specialty coffee',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }

        $this->command->info('✅ Partners berhasil di-seed!');
    }
}
