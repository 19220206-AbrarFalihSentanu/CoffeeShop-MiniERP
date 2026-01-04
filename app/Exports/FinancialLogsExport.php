<?php
// File: app/Exports/FinancialLogsExport.php

namespace App\Exports;

use App\Models\FinancialLog;
use Illuminate\Support\Collection;

class FinancialLogsExport
{
    protected $startDate;
    protected $endDate;
    protected $category;

    public function __construct($startDate, $endDate, $category = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->category = $category;
    }

    /**
     * Get the data for export
     */
    public function collection(): Collection
    {
        $query = FinancialLog::with(['creator'])
            ->byDateRange($this->startDate, $this->endDate);

        if ($this->category) {
            $query->where('category', $this->category);
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    /**
     * Export using Fast Excel - returns mapped data
     */
    public function export(): Collection
    {
        return $this->collection()->map(function ($log) {
            return [
                'Date' => $log->transaction_date->format('d/m/Y'),
                'Type' => $log->type_display,
                'Category' => $log->category_display,
                'Amount (Rp)' => number_format($log->amount, 0, ',', '.'),
                'Description' => $log->description,
                'Created By' => $log->creator->name ?? '-',
                'Reference' => $log->reference_type
                    ? class_basename($log->reference_type) . ' #' . $log->reference_id
                    : 'Manual Entry',
            ];
        });
    }

    /**
     * Get totals for the export
     */
    public function getTotals(): array
    {
        $collection = $this->collection();

        return [
            'income' => $collection->where('type', 'income')->sum('amount'),
            'expense' => $collection->where('type', 'expense')->sum('amount'),
            'net' => $collection->where('type', 'income')->sum('amount') - $collection->where('type', 'expense')->sum('amount'),
        ];
    }
}
