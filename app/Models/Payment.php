<?php
// File: app/Models/Payment.php
// Jalankan: php artisan make:model Payment

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_proof',
        'customer_notes',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Status Check Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function canVerify(): bool
    {
        return $this->status === 'pending';
    }

    // Display Methods
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'pending' => 'bg-warning',
            'verified' => 'bg-success',
            'rejected' => 'bg-danger',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getPaymentMethodDisplayAttribute(): string
    {
        $methods = [
            'transfer_bank' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'cash' => 'Cash/Tunai',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
