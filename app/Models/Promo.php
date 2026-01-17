<?php
// File: app/Models/Promo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'discount_type',
        'discount_value',
        'promo_code',
        'start_date',
        'end_date',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'discount_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Scope active promos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope ordered promos
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/img/placeholder-promo.png');
    }

    /**
     * Get formatted discount
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }
        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }

    /**
     * Check if promo is currently valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }
}
