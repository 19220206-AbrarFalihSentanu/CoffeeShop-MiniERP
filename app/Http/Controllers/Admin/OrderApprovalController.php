<?php
// File: app/Http/Controllers/Admin/OrderApprovalController.php
// Jalankan: php artisan make:controller Admin/OrderApprovalController

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderApproved;
use App\Mail\OrderRejected;
use App\Models\Order;
use App\Models\FinancialLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderApprovalController extends Controller
{
    /**
     * Display orders waiting for approval
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Filter by status - if 'status' is provided, filter by it
        // If empty/not provided, show ALL orders (not just pending)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // No else - show all orders when no filter selected

        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage)->withQueryString();

        return view('admin.orders.approval.index', compact('orders'));
    }

    /**
     * Show order detail for approval
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product.inventory', 'approver']);

        return view('admin.orders.approval.show', compact('order'));
    }

    /**
     * Approve order
     */
    public function approve(Request $request, Order $order)
    {
        $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
            'shipping_cost' => 'nullable|numeric|min:0',
        ], [
            'due_date.required' => 'Tanggal jatuh tempo harus diisi.',
            'due_date.date' => 'Format tanggal tidak valid.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum hari ini.',
            'shipping_cost.numeric' => 'Ongkir harus berupa angka.',
            'shipping_cost.min' => 'Ongkir tidak boleh negatif.',
        ]);

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
            // Handle shipping cost override
            if ($request->filled('shipping_cost')) {
                $newShippingCost = (float) $request->shipping_cost;
                $shippingDifference = $newShippingCost - $order->shipping_cost;

                // Recalculate total amount
                $order->shipping_cost = $newShippingCost;
                $order->total_amount = $order->subtotal + $order->tax_amount + $order->shipping_cost;
            }

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
                'due_date' => $request->due_date,
                'shipping_cost' => $order->shipping_cost,
                'total_amount' => $order->total_amount,
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

            // Auto-generate Invoice PDF
            $invoiceController = new \App\Http\Controllers\Admin\InvoiceController();
            $invoiceController->generate($order);

            DB::commit();

            // Send email to customer with invoice attachment (async queue)
            if ($order->customer_email) {
                Mail::to($order->customer_email)->queue(new OrderApproved($order));
            }

            return redirect()->route('admin.orders.approval.show', $order)
                ->with('success', 'Order berhasil disetujui! Stok telah dikurangi. Invoice telah dibuat.');
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

            // Send email to customer
            if ($order->customer_email) {
                Mail::to($order->customer_email)->queue(new OrderRejected($order));
            }

            return redirect()->route('admin.orders.approval.show', $order)
                ->with('success', 'Order berhasil ditolak. Stok reserved telah dilepas. Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reject order: ' . $e->getMessage());
        }
    }
}
