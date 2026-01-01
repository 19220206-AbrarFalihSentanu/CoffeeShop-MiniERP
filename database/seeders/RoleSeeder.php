<?php

// File: database/seeders/RoleSeeder.php
// Jalankan: php artisan make:seeder RoleSeeder

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'description' => 'Pemilik bisnis dengan akses penuh dan approval authority'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator yang mengelola operasional harian'
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Pelanggan yang dapat melakukan pemesanan'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        $this->command->info('✅ Roles berhasil di-seed!');
    }
}

