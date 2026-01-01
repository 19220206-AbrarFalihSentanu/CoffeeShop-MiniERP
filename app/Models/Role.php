<?php

// File: app/Models/Role.php
// Jalankan: php artisan make:model Role

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    // Konstanta untuk role names
    const OWNER = 'owner';
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Helper method untuk check role
    public function isOwner(): bool
    {
        return $this->name === self::OWNER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isCustomer(): bool
    {
        return $this->name === self::CUSTOMER;
    }
}
