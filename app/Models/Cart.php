<?php
// File: app/Models/Cart.php
// Jalankan: php artisan make:model Cart

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor: Get subtotal for this cart item
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Check if product still available with requested quantity
    public function isAvailable(): bool
    {
        if (!$this->product || !$this->product->is_active) {
            return false;
        }

        return $this->product->hasStock($this->quantity);
    }

    // Get current product price (might be different from cart price)
    public function getCurrentProductPrice()
    {
        return $this->product ? $this->product->final_price : $this->price;
    }

    // Check if price has changed since added to cart
    public function hasPriceChanged(): bool
    {
        return $this->price != $this->getCurrentProductPrice();
    }

    // Scope: Get cart for specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope: Get cart with available products only
    public function scopeAvailable($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('is_active', true)
                ->whereHas('inventory', function ($inv) {
                    $inv->whereRaw('(quantity - reserved) > 0');
                });
        });
    }
}
