<?php
// File: app/Http/Controllers/Customer/OrderController.php
// Jalankan: php artisan make:controller Customer/OrderController

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display customer's orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'approver'])
            ->forCustomer(auth()->id())
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        // Ensure customer owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['items.product', 'approver']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Upload payment proof
     */
    public function uploadPayment(Request $request, Order $order)
    {
        // Ensure customer owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canUploadPayment()) {
            return back()->with('error', 'Order ini belum dapat melakukan pembayaran. Status: ' . $order->status_display);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:transfer_bank,e_wallet,cash'],
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        try {
            // Delete old payment proof if exists
            if ($order->payment_proof) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Upload new payment proof
            $path = $request->file('payment_proof')->store('payments', 'public');

            $order->update([
                'payment_method' => $validated['payment_method'],
                'payment_proof' => $path,
            ]);

            // TODO: Send email to admin
            // Mail::to(admin email)->send(new PaymentProofUploadedMail($order));

            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order (only if pending)
     */
    public function cancel(Order $order)
    {
        // Ensure customer owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isPending()) {
            return back()->with('error', 'Order tidak dapat dibatalkan. Status: ' . $order->status_display);
        }

        try {
            // Release reserved stock
            foreach ($order->items as $item) {
                if ($item->product && $item->product->inventory) {
                    $item->product->inventory->releaseReserved($item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
            ]);

            return back()->with('success', 'Order berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }
}
