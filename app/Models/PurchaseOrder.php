<?php
// File: app/Models/PurchaseOrder.php
// Jalankan: php artisan make:model PurchaseOrder

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'created_by',
        'approved_by',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'expected_delivery_date',
        'notes',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [
        'expected_delivery_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function financialLogs(): MorphMany
    {
        return $this->morphMany(FinancialLog::class, 'reference');
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'source_id')
            ->where('source', 'purchase_order');
    }

    // Permission Check Methods
    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canDelete(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canSubmit(): bool
    {
        // FIX: Draft ATAU Rejected bisa di-submit
        // Draft: belum pernah submit
        // Rejected: ditolak, perlu submit ulang
        $validStatuses = ['draft', 'rejected'];

        return in_array($this->status, $validStatuses)
            && $this->items()->count() > 0;
    }

    public function canApprove(): bool
    {
        return $this->status === 'pending';
    }

    public function canReceive(): bool
    {
        return $this->status === 'approved';
    }

    // Display Methods
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'draft' => 'bg-secondary',
            'pending' => 'bg-warning',
            'approved' => 'bg-info',
            'rejected' => 'bg-danger',
            'completed' => 'bg-success',
            'cancelled' => 'bg-dark',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function isFullyReceived(): bool
    {
        foreach ($this->items as $item) {
            if ($item->quantity_received < $item->quantity_ordered) {
                return false;
            }
        }
        return $this->items()->count() > 0;
    }

    public function getReceiveProgressAttribute(): float
    {
        if ($this->items()->count() === 0) {
            return 0;
        }

        $totalOrdered = $this->items()->sum('quantity_ordered');
        $totalReceived = $this->items()->sum('quantity_received');

        if ($totalOrdered == 0) {
            return 0;
        }

        return ($totalReceived / $totalOrdered) * 100;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('po_number', 'like', "%{$search}%")
                ->orWhereHas('supplier', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
        });
    }

    /**
     * Check if PO is rejected and can be resubmitted
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if PO was rejected before
     */
    public function wasRejectedBefore(): bool
    {
        return !empty($this->rejected_at);
    }

    /**
     * Reset rejection data (untuk re-submit)
     */
    public function resetRejection(): void
    {
        $this->update([
            'approved_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }

    // Boot method untuk auto-generate PO number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->po_number)) {
                $model->po_number = self::generatePoNumber();
            }
        });
    }

    public static function generatePoNumber(): string
    {
        $date = now()->format('Ymd');
        $lastPo = self::whereDate('created_at', today())
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPo ? intval(substr($lastPo->po_number, -3)) + 1 : 1;

        return 'PO-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
