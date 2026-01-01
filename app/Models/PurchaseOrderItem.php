<?php
// File: app/Models/PurchaseOrderItem.php
// Jalankan: php artisan make:model PurchaseOrderItem

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    public function getReceivePercentageAttribute(): float
    {
        if ($this->quantity_ordered == 0) {
            return 0;
        }

        return ($this->quantity_received / $this->quantity_ordered) * 100;
    }

    public function isFullyReceived(): bool
    {
        return $this->quantity_received >= $this->quantity_ordered;
    }

    // Boot method untuk auto-calculate subtotal
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto calculate subtotal
            $model->subtotal = $model->quantity_ordered * $model->unit_price;
        });

        static::saved(function ($model) {
            // Update PO total setelah item disave
            $model->updatePurchaseOrderTotal();
        });

        static::deleted(function ($model) {
            // Update PO total setelah item dihapus
            $model->updatePurchaseOrderTotal();
        });
    }

    protected function updatePurchaseOrderTotal()
    {
        $po = $this->purchaseOrder;

        if ($po) {
            $subtotal = $po->items()->sum('subtotal');
            $taxRate = (float) setting('tax_rate', 11); // Default 11%
            $taxAmount = $subtotal * ($taxRate / 100);
            $total = $subtotal + $taxAmount;

            $po->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
            ]);
        }
    }
}
