<?php
// File: app/Http/Controllers/Admin/ReceivePurchaseOrderController.php
// Jalankan: php artisan make:controller Admin/ReceivePurchaseOrderController

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Inventory;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivePurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $purchaseOrders = PurchaseOrder::with(['supplier', 'creator', 'items'])
            ->approved()
            ->orderBy('approved_at', 'desc')
            ->paginate($perPage)->withQueryString();

        return view('admin.purchase-orders.receive.index', compact('purchaseOrders'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canReceive()) {
            // Redirect ke halaman detail PO, bukan back() untuk menghindari redirect loop
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Purchase Order ini tidak dapat di-receive. Status: ' . $purchaseOrder->status_display);
        }

        $purchaseOrder->load(['supplier', 'items.product.inventory']);

        return view('admin.purchase-orders.receive.show', compact('purchaseOrder'));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canReceive()) {
            // Redirect ke halaman detail PO, bukan back() untuk menghindari redirect loop
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Purchase Order ini tidak dapat di-receive. Status: ' . $purchaseOrder->status_display);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $totalReceived = 0;

            foreach ($validated['items'] as $itemData) {
                $poItem = $purchaseOrder->items()->find($itemData['item_id']);

                if (!$poItem) {
                    throw new \Exception("Item tidak ditemukan dalam Purchase Order ini.");
                }

                $qtyToReceive = $itemData['quantity_received'];

                if ($qtyToReceive <= 0) {
                    continue; // Skip if quantity is 0
                }

                // Check if quantity_received doesn't exceed quantity_ordered
                $maxReceivable = $poItem->quantity_ordered - $poItem->quantity_received;
                if ($qtyToReceive > $maxReceivable) {
                    throw new \Exception("Jumlah yang diterima untuk {$poItem->product->name} melebihi jumlah yang dipesan.");
                }

                // Update PO Item
                $poItem->update([
                    'quantity_received' => $poItem->quantity_received + $qtyToReceive,
                ]);

                // Update Inventory
                $inventory = Inventory::where('product_id', $poItem->product_id)->first();
                $beforeQty = $inventory->quantity;
                $afterQty = $beforeQty + $qtyToReceive;

                $inventory->update([
                    'quantity' => $afterQty,
                ]);

                // Create Inventory Log
                InventoryLog::create([
                    'product_id' => $poItem->product_id,
                    'user_id' => auth()->id(),
                    'type' => 'in',
                    'quantity' => $qtyToReceive,
                    'before' => $beforeQty,
                    'after' => $afterQty,
                    'notes' => "Receive dari PO {$purchaseOrder->po_number}" . ($request->notes ? " - {$request->notes}" : ""),
                    'reference' => "PO-{$purchaseOrder->po_number}",
                    'source' => 'purchase_order',
                    'source_id' => $purchaseOrder->id,
                ]);

                $totalReceived++;
            }

            // Check if all items are fully received
            if ($purchaseOrder->isFullyReceived()) {
                $purchaseOrder->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            DB::commit();

            $message = "Berhasil menerima stok untuk {$totalReceived} item.";
            if ($purchaseOrder->status === 'completed') {
                $message .= " Purchase Order telah selesai!";
            }

            return redirect()->route('admin.purchase-orders.receive.show', $purchaseOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menerima stok: ' . $e->getMessage());
        }
    }
}

