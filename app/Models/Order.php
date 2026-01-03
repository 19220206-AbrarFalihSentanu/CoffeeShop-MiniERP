<?php
// File: app/Models/Order.php
// Jalankan: php artisan make:model Order

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_at',
        'payment_method',
        'payment_proof',
        'paid_at',
        'tracking_number',
        'shipped_at',
        'completed_at',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function financialLog(): MorphOne
    {
        return $this->morphOne(FinancialLog::class, 'reference');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Status Check Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canApprove(): bool
    {
        return $this->status === 'pending';
    }

    public function canProcess(): bool
    {
        return $this->status === 'paid';
    }

    public function canShip(): bool
    {
        return $this->status === 'processing';
    }

    public function canComplete(): bool
    {
        return $this->status === 'shipped';
    }

    public function hasPayment(): bool
    {
        return $this->payment !== null;
    }

    public function canUploadPayment(): bool
    {
        return $this->status === 'approved' && !$this->hasPayment();
    }

    public function canReuploadPayment(): bool
    {
        return $this->status === 'approved'
            && $this->hasPayment()
            && $this->payment->isRejected();
    }

    // Display Methods
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'pending' => 'bg-warning',
            'approved' => 'bg-info',
            'rejected' => 'bg-danger',
            'paid' => 'bg-success',
            'processing' => 'bg-primary',
            'shipped' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-dark',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui - Menunggu Pembayaran',
            'rejected' => 'Ditolak',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeWaitingPayment($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Auto-generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? intval(substr($lastOrder->order_number, -3)) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
