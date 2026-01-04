<?php
// File: app/Models/Inventory.php
// Jalankan: php artisan make:model Inventory

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'reserved'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'reserved' => 'decimal:3'
    ];

    // Relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor untuk stok tersedia (virtual column)
    public function getAvailableAttribute()
    {
        return $this->quantity - $this->reserved;
    }

    // Format stok dengan satuan dari produk
    public function getFormattedQuantityAttribute(): string
    {
        return $this->product ? $this->product->formatQuantity($this->quantity) : $this->quantity;
    }

    public function getFormattedAvailableAttribute(): string
    {
        return $this->product ? $this->product->formatQuantity($this->available) : $this->available;
    }

    // Helper: Tambah stok
    public function addStock($quantity, $notes = null, $reference = null)
    {
        $before = $this->quantity;
        $this->quantity += $quantity;
        $this->save();

        // Log perubahan
        InventoryLog::create([
            'product_id' => $this->product_id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $quantity,
            'before' => $before,
            'after' => $this->quantity,
            'notes' => $notes,
            'reference' => $reference
        ]);

        return $this;
    }

    // Helper: Kurangi stok
    public function reduceStock($quantity, $notes = null, $reference = null)
    {
        if ($this->available < $quantity) {
            throw new \Exception('Stok tidak mencukupi');
        }

        $before = $this->quantity;
        $this->quantity -= $quantity;
        $this->save();

        // Log perubahan
        InventoryLog::create([
            'product_id' => $this->product_id,
            'user_id' => auth()->id(),
            'type' => 'out',
            'quantity' => -$quantity,
            'before' => $before,
            'after' => $this->quantity,
            'notes' => $notes,
            'reference' => $reference
        ]);

        return $this;
    }

    // Helper: Reserve stok (untuk order yang belum dibayar)
    public function reserveStock($quantity)
    {
        if ($this->available < $quantity) {
            throw new \Exception('Stok tidak mencukupi');
        }

        $this->reserved += $quantity;
        $this->save();

        return $this;
    }

    // Helper: Release reserved stok
    public function releaseReserved($quantity)
    {
        $this->reserved = max(0, $this->reserved - $quantity);
        $this->save();

        return $this;
    }
}
