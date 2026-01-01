<?php
// ============================================================
// File: app/Models/Category.php
// Jalankan: php artisan make:model Category
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Auto-generate slug when saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationship dengan products (akan dibuat di fase berikutnya)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Helper: Cek apakah category masih punya produk
    public function hasProducts()
    {
        return $this->products()->count() > 0;
    }
}
