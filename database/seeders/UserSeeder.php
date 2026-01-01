<?php

// File: database/seeders/UserSeeder.php
// Jalankan: php artisan make:seeder UserSeeder

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Buat Owner
        User::updateOrCreate(
            ['email' => 'owner@kopi.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role_id' => $ownerRole->id,
                'phone' => '081234567890',
                'address' => 'Jl. Kopi No. 1, Jakarta',
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        // Buat Admin
        User::updateOrCreate(
            ['email' => 'admin@kopi.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'phone' => '081234567891',
                'address' => 'Jl. Kopi No. 2, Jakarta',
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        // Buat Customer Demo
        User::updateOrCreate(
            ['email' => 'customer@kopi.com'],
            [
                'name' => 'Ahmad Yani',
                'password' => Hash::make('password123'),
                'role_id' => $customerRole->id,
                'phone' => '081234567892',
                'address' => 'Jl. Customer No. 3, Jakarta',
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        $this->command->info('✅ Default users berhasil dibuat!');
        $this->command->info('📧 Owner: owner@kopi.com | Password: password123');
        $this->command->info('📧 Admin: admin@kopi.com | Password: password123');
        $this->command->info('📧 Customer: customer@kopi.com | Password: password123');
    }
}
