<?php
// File: app/Exports/InventoryReportExport.php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;

class InventoryReportExport
{
    protected $category;
    protected $stockStatus;

    public function __construct($category = null, $stockStatus = null)
    {
        $this->category = $category;
        $this->stockStatus = $stockStatus;
    }

    /**
     * Get the data for export
     */
    public function collection(): Collection
    {
        $query = Product::with(['category', 'inventory']);

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->stockStatus) {
            switch ($this->stockStatus) {
                case 'low':
                    $query->whereHas('inventory', function ($q) {
                        $q->whereRaw('(quantity - reserved) <= products.min_stock')
                            ->whereRaw('(quantity - reserved) > 0');
                    });
                    break;
                case 'out':
                    $query->whereHas('inventory', function ($q) {
                        $q->whereRaw('(quantity - reserved) <= 0');
                    });
                    break;
            }
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Export using Fast Excel - returns mapped data
     */
    public function export(): Collection
    {
        return $this->collection()->map(function ($product) {
            $inventory = $product->inventory;

            $status = 'OK';
            if (!$inventory || $inventory->available <= 0) {
                $status = 'Out of Stock';
            } elseif ($inventory->available <= $product->min_stock) {
                $status = 'Low Stock';
            }

            return [
                'SKU' => $product->sku,
                'Product Name' => $product->name,
                'Category' => $product->category->name ?? '-',
                'Type' => ucfirst(str_replace('_', ' ', $product->type)),
                'Weight (g)' => $product->weight,
                'Cost Price' => number_format($product->cost_price, 0, ',', '.'),
                'Selling Price' => number_format($product->price, 0, ',', '.'),
                'Total Stock' => $inventory ? $inventory->quantity : 0,
                'Reserved' => $inventory ? $inventory->reserved : 0,
                'Available' => $inventory ? $inventory->available : 0,
                'Min Stock' => $product->min_stock,
                'Status' => $status,
            ];
        });
    }

    /**
     * Get statistics for the report
     */
    public function getStats(): array
    {
        $collection = $this->collection();

        return [
            'total_products' => $collection->count(),
            'low_stock' => $collection->filter(function ($product) {
                $inventory = $product->inventory;
                return $inventory && $inventory->available > 0 && $inventory->available <= $product->min_stock;
            })->count(),
            'out_of_stock' => $collection->filter(function ($product) {
                $inventory = $product->inventory;
                return !$inventory || $inventory->available <= 0;
            })->count(),
            'total_value' => $collection->sum(function ($product) {
                return $product->cost_price * ($product->inventory ? $product->inventory->available : 0);
            }),
        ];
    }
}
