<?php
// File: app/Models/FinancialLog.php
// Jalankan: php artisan make:model FinancialLog

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'amount',
        'reference_type',
        'reference_id',
        'description',
        'created_by',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    // Relationships
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Methods
    public function getTypeDisplayAttribute(): string
    {
        $types = [
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getTypeBadgeClassAttribute(): string
    {
        $classes = [
            'income' => 'bg-success',
            'expense' => 'bg-danger',
        ];

        return $classes[$this->type] ?? 'bg-secondary';
    }

    public function getCategoryDisplayAttribute(): string
    {
        $categories = [
            'purchase' => 'Pembelian Stok',
            'sales' => 'Penjualan',
            'operational' => 'Operasional',
            'salary' => 'Gaji',
            'other' => 'Lainnya',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('transaction_date', now()->year);
    }
}
