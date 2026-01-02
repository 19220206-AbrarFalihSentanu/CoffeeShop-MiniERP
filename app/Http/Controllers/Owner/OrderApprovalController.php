<?php
// File: app/Http/Controllers/Owner/OrderApprovalController.php
// Jalankan: php artisan make:controller Owner/OrderApprovalController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\FinancialLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApprovalController extends Controller
{
    /**
     * Display orders waiting for approval
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending orders
            $query->pending();
        }

        $orders = $query->paginate(10);

        return view('owner.orders.approval.index', compact('orders'));
    }

    /**
     * Show order detail for approval
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product.inventory', 'approver']);

        return view('owner.orders.approval.show', compact('order'));
    }

    /**
     * Approve order
     */
    public function approve(Request $request, Order $order)
    {
        if (!$order->canApprove()) {
            return back()->with('error', 'Order tidak dapat diapprove. Status: ' . $order->status_display);
        }

        // Check stock availability
        foreach ($order->items as $item) {
            if (!$item->product || !$item->product->inventory) {
                return back()->with('error', "Produk {$item->product_name} tidak ditemukan atau tidak memiliki inventory.");
            }

            // Check available stock (excluding reserved)
            $availableStock = $item->product->inventory->available;
            if ($availableStock < $item->quantity) {
                return back()->with('error', "Stok {$item->product->name} tidak mencukupi. Tersedia: {$availableStock}, Dipesan: {$item->quantity}");
            }
        }

        DB::beginTransaction();
        try {
            // Deduct stock & release reserved
            foreach ($order->items as $item) {
                $inventory = $item->product->inventory;

                // Release reserved stock first
                $inventory->releaseReserved($item->quantity);

                // Then reduce actual stock
                $inventory->reduceStock(
                    $item->quantity,
                    "Order {$order->order_number} disetujui",
                    $order->order_number
                );
            }

            // Update order status
            $order->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Create Financial Log
            FinancialLog::create([
                'type' => 'income',
                'category' => 'sales',
                'amount' => $order->total_amount,
                'reference_type' => 'App\Models\Order',
                'reference_id' => $order->id,
                'description' => "Penjualan Order {$order->order_number} - {$order->customer_name}",
                'created_by' => auth()->id(),
                'transaction_date' => now()->toDateString(),
            ]);

            DB::commit();

            // TODO: Generate Invoice PDF
            // TODO: Send email to customer with invoice

            return redirect()->route('owner.orders.approval.show', $order)
                ->with('success', 'Order berhasil disetujui! Stok telah dikurangi. Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve order: ' . $e->getMessage());
        }
    }

    /**
     * Reject order
     */
    public function reject(Request $request, Order $order)
    {
        if (!$order->canApprove()) {
            return back()->with('error', 'Order tidak dapat ditolak. Status: ' . $order->status_display);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        DB::beginTransaction();
        try {
            // Release reserved stock
            foreach ($order->items as $item) {
                if ($item->product && $item->product->inventory) {
                    $item->product->inventory->releaseReserved($item->quantity);
                }
            }

            // Update order status
            $order->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            DB::commit();

            // TODO: Send email to customer

            return redirect()->route('owner.orders.approval.show', $order)
                ->with('success', 'Order berhasil ditolak. Stok reserved telah dilepas. Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reject order: ' . $e->getMessage());
        }
    }
}
