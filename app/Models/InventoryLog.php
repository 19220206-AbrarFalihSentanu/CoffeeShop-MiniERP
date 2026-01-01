<?php
// File: app/Models/InventoryLog.php
// Jalankan: php artisan make:model InventoryLog

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'before',
        'after',
        'notes',
        'reference'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'before' => 'integer',
        'after' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk tipe yang lebih readable
    public function getTypeNameAttribute()
    {
        return match ($this->type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            default => $this->type
        };
    }
}
