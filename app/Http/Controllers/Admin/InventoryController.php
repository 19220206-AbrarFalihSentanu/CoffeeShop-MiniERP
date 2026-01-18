<?php
// File: app/Http/Controllers/Admin/InventoryController.php
// Copy dari Owner/InventoryController dengan namespace berbeda

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display inventory overview
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory'])
            ->whereHas('inventory');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereHas('inventory', function ($q) {
                        $q->whereRaw('(quantity - reserved) <= products.min_stock');
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

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->latest()->paginate($perPage)->withQueryString();
        $categories = \App\Models\Category::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total_products' => Product::whereHas('inventory')->count(),
            'low_stock' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock')
                    ->whereRaw('(quantity - reserved) > 0');
            })->count(),
            'out_of_stock' => Product::whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= 0');
            })->count(),
            'total_value' => Product::with('inventory')->get()->sum(function ($product) {
                return $product->cost_price * ($product->inventory ? $product->inventory->available : 0);
            })
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show form for stock adjustment
     */
    public function adjust(Product $product)
    {
        $product->load('inventory', 'category');
        return view('admin.inventory.adjust', compact('product'));
    }

    /**
     * Process stock adjustment (in/out)
     */
    public function processAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
            'reference' => ['nullable', 'string', 'max:100']
        ]);

        try {
            DB::beginTransaction();

            $inventory = $product->inventory;
            if (!$inventory) {
                return back()->with('error', 'Inventory tidak ditemukan untuk produk ini.');
            }

            $type = $validated['type'];
            $quantity = $validated['quantity'];

            // Process based on type
            if ($type === 'in') {
                $inventory->addStock(
                    $quantity,
                    $validated['notes'] ?? "Stock In - " . now()->format('d/m/Y H:i'),
                    $validated['reference'] ?? null
                );
                $message = "Berhasil menambahkan {$quantity} unit stok.";
            } elseif ($type === 'out') {
                // Check available stock
                if ($inventory->available < $quantity) {
                    DB::rollBack();
                    return back()->with('error', "Stok tidak mencukupi. Stok tersedia: {$inventory->available} unit");
                }

                $inventory->reduceStock(
                    $quantity,
                    $validated['notes'] ?? "Stock Out - " . now()->format('d/m/Y H:i'),
                    $validated['reference'] ?? null
                );
                $message = "Berhasil mengurangi {$quantity} unit stok.";
            } else { // adjustment
                $before = $inventory->quantity;
                $inventory->quantity = $quantity;
                $inventory->save();

                // Log adjustment
                InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'adjustment',
                    'quantity' => $quantity - $before,
                    'before' => $before,
                    'after' => $quantity,
                    'notes' => $validated['notes'] ?? "Stock Adjustment - " . now()->format('d/m/Y H:i'),
                    'reference' => $validated['reference'] ?? null
                ]);

                $message = "Berhasil menyesuaikan stok menjadi {$quantity} unit.";
            }

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Show inventory logs
     */
    public function logs(Request $request)
    {
        $query = InventoryLog::with(['product', 'user']);

        // Filter by product
        if ($request->has('product_id') && $request->product_id != '') {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by reference or notes
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $logs = $query->latest()->paginate($perPage)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('admin.inventory.logs', compact('logs', 'products'));
    }

    /**
     * Show bulk adjustment form
     */
    public function bulkAdjust()
    {
        $products = Product::with('inventory')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.inventory.bulk-adjust', compact('products'));
    }

    /**
     * Process bulk adjustment
     */
    public function processBulkAdjustment(Request $request)
    {
        $validated = $request->validate([
            'adjustments' => ['required', 'array'],
            'adjustments.*.product_id' => ['required', 'exists:products,id'],
            'adjustments.*.quantity' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500']
        ]);

        try {
            DB::beginTransaction();

            $processed = 0;
            foreach ($validated['adjustments'] as $adjustment) {
                $product = Product::find($adjustment['product_id']);
                $inventory = $product->inventory;

                if (!$inventory) {
                    continue;
                }

                $before = $inventory->quantity;
                $newQuantity = $adjustment['quantity'];

                if ($before != $newQuantity) {
                    $inventory->quantity = $newQuantity;
                    $inventory->save();

                    // Log adjustment
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'adjustment',
                        'quantity' => $newQuantity - $before,
                        'before' => $before,
                        'after' => $newQuantity,
                        'notes' => $validated['notes'] ?? "Bulk Stock Adjustment - " . now()->format('d/m/Y H:i'),
                        'reference' => 'BULK-' . now()->format('YmdHis')
                    ]);

                    $processed++;
                }
            }

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', "Berhasil memproses {$processed} produk untuk bulk adjustment.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses bulk adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Export inventory report to Excel
     */
    public function export(Request $request)
    {
        $category = $request->get('category');
        $stockStatus = $request->get('stock_status');

        $export = new \App\Exports\InventoryReportExport($category, $stockStatus);
        $filename = 'Inventory_Report_' . now()->format('Y-m-d_His') . '.xlsx';

        return (new \Rap2hpoutre\FastExcel\FastExcel($export->export()))->download($filename);
    }

    /**
     * Get low stock alerts
     */
    public function alerts()
    {
        $lowStockProducts = Product::with(['category', 'inventory'])
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= products.min_stock')
                    ->whereRaw('(quantity - reserved) > 0');
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $outOfStockProducts = Product::with(['category', 'inventory'])
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) <= 0');
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.inventory.alerts', compact('lowStockProducts', 'outOfStockProducts'));
    }
}

