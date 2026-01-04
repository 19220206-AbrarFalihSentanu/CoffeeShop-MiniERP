<?php
// File: app/Models/Product.php
// Jalankan: php artisan make:model Product

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'description',
        'type',
        'weight',
        'unit',
        'min_order_qty',
        'order_increment',
        'cost_price',
        'price',
        'has_discount',
        'discount_type',
        'discount_value',
        'discount_start_date',
        'discount_end_date',
        'image',
        'images',
        'min_stock',
        'is_active',
        'is_featured'
    ];

    // Satuan yang tersedia
    const UNITS = [
        'gram' => 'Gram (g)',
        'kg' => 'Kilogram (kg)',
        'ton' => 'Ton',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'min_order_qty' => 'decimal:3',
        'order_increment' => 'decimal:3',
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'has_discount' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'discount_start_date' => 'date',
        'discount_end_date' => 'date'
    ];

    // Accessor untuk label satuan
    public function getUnitLabelAttribute(): string
    {
        return self::UNITS[$this->unit] ?? $this->unit;
    }

    // Format quantity dengan satuan
    public function formatQuantity($qty): string
    {
        $formatted = rtrim(rtrim(number_format($qty, 3, ',', '.'), '0'), ',');
        return $formatted . ' ' . $this->unit;
    }

    // Auto-generate slug dan SKU
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }

            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Accessor untuk harga setelah diskon
    public function getFinalPriceAttribute()
    {
        if (!$this->isDiscountActive()) {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            return $this->price - ($this->price * $this->discount_value / 100);
        }

        return $this->price - $this->discount_value;
    }

    // Accessor untuk persentase diskon (untuk display)
    public function getDiscountPercentageAttribute()
    {
        if (!$this->isDiscountActive()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_value;
        }

        // Hitung persentase dari nominal diskon
        return ($this->discount_value / $this->price) * 100;
    }

    // Accessor untuk jumlah hemat
    public function getSavingsAmountAttribute()
    {
        if (!$this->isDiscountActive()) {
            return 0;
        }

        return $this->price - $this->final_price;
    }

    // Helper: Cek apakah diskon aktif
    public function isDiscountActive()
    {
        if (!$this->has_discount) {
            return false;
        }

        $now = Carbon::now()->startOfDay();

        // Cek tanggal diskon
        if ($this->discount_start_date && $now->lt($this->discount_start_date)) {
            return false;
        }

        if ($this->discount_end_date && $now->gt($this->discount_end_date)) {
            return false;
        }

        return true;
    }

    // Helper: Cek stok tersedia
    public function hasStock($quantity = 1)
    {
        return $this->inventory && $this->inventory->available >= $quantity;
    }

    // Helper: Get stok tersedia
    public function getAvailableStock()
    {
        return $this->inventory ? $this->inventory->available : 0;
    }

    // Helper: Cek apakah stok menipis
    public function isLowStock()
    {
        return $this->getAvailableStock() <= $this->min_stock;
    }

    // Scope: Produk aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Produk dengan diskon
    public function scopeOnSale($query)
    {
        $now = Carbon::now()->startOfDay();

        return $query->where('has_discount', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('discount_start_date')
                    ->orWhere('discount_start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('discount_end_date')
                    ->orWhere('discount_end_date', '>=', $now);
            });
    }

    // Scope: Produk featured
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
