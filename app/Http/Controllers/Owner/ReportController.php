<?php
// File: app/Http/Controllers/Owner/ReportController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FinancialLog;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Exports\FinancialLogsExport;
use App\Exports\InventoryReportExport;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Mpdf\Mpdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Financial Reports Page
     */
    public function financial(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $category = $request->get('category');

        $query = FinancialLog::with(['creator'])
            ->byDateRange($startDate, $endDate);

        if ($category) {
            $query->where('category', $category);
        }

        $logs = $query->orderBy('transaction_date', 'desc')->paginate(20);

        // Totals
        $totals = [
            'income' => FinancialLog::income()->byDateRange($startDate, $endDate)
                ->when($category, fn($q) => $q->where('category', $category))
                ->sum('amount'),
            'expense' => FinancialLog::expense()->byDateRange($startDate, $endDate)
                ->when($category, fn($q) => $q->where('category', $category))
                ->sum('amount'),
        ];
        $totals['net'] = $totals['income'] - $totals['expense'];

        $categories = FinancialLog::distinct()->pluck('category');

        return view('owner.reports.financial', compact(
            'logs',
            'totals',
            'startDate',
            'endDate',
            'category',
            'categories'
        ));
    }

    /**
     * Export Financial Report to Excel using FastExcel
     */
    public function exportFinancialExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $category = $request->get('category');

        $export = new FinancialLogsExport($startDate, $endDate, $category);
        $filename = 'Financial_Report_' . $startDate . '_to_' . $endDate . '.xlsx';

        return (new FastExcel($export->export()))->download($filename);
    }

    /**
     * Export Financial Report to PDF using mPDF
     */
    public function exportFinancialPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $category = $request->get('category');

        $query = FinancialLog::with(['creator'])
            ->byDateRange($startDate, $endDate);

        if ($category) {
            $query->where('category', $category);
        }

        $logs = $query->orderBy('transaction_date', 'desc')->get();

        $totals = [
            'income' => $logs->where('type', 'income')->sum('amount'),
            'expense' => $logs->where('type', 'expense')->sum('amount'),
        ];
        $totals['net'] = $totals['income'] - $totals['expense'];

        $data = compact('logs', 'totals', 'startDate', 'endDate', 'category');

        $html = view('reports.financial-pdf', $data)->render();

        $mpdf = new Mpdf([
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        $filename = 'Financial_Report_' . $startDate . '_to_' . $endDate . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Inventory Reports Page
     */
    public function inventory(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
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
                case 'available':
                    $query->whereHas('inventory', function ($q) {
                        $q->whereRaw('(quantity - reserved) > products.min_stock');
                    });
                    break;
            }
        }

        $products = $query->orderBy('name')->paginate(20);
        $categories = \App\Models\Category::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'low_stock' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock')
                    ->whereRaw('(quantity - reserved) > 0');
            })->count(),
            'out_of_stock' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= 0');
            })->count(),
            'total_value' => Product::with('inventory')->get()->sum(function ($product) {
                return $product->cost_price * ($product->inventory ? $product->inventory->available : 0);
            }),
        ];

        return view('owner.reports.inventory', compact('products', 'categories', 'stats'));
    }

    /**
     * Export Inventory Report to Excel using FastExcel
     */
    public function exportInventoryExcel(Request $request)
    {
        $category = $request->get('category');
        $stockStatus = $request->get('stock_status');

        $export = new InventoryReportExport($category, $stockStatus);
        $filename = 'Inventory_Report_' . now()->format('Y-m-d') . '.xlsx';

        return (new FastExcel($export->export()))->download($filename);
    }

    /**
     * Export Inventory Report to PDF using mPDF
     */
    public function exportInventoryPdf(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
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

        $products = $query->orderBy('name')->get();

        $html = view('reports.inventory-pdf', compact('products'))->render();

        $mpdf = new Mpdf([
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        $filename = 'Inventory_Report_' . now()->format('Y-m-d') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
