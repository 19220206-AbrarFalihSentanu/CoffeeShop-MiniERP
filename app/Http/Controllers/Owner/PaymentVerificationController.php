<?php
// File: app/Http/Controllers/Owner/PaymentVerificationController.php
// Jalankan: php artisan make:controller Owner/PaymentVerificationController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Mail\PaymentVerified;
use App\Mail\PaymentRejected;
use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.customer', 'verifier'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending and verified payments
            $query->whereIn('status', ['pending', 'verified']);
        }

        // Search by order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(10);

        return view('owner.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.customer', 'order.items.product', 'verifier']);

        return view('owner.payments.show', compact('payment'));
    }

    public function verify(Request $request, Payment $payment)
    {
        if (!$payment->canVerify()) {
            return back()->with('error', 'Payment tidak dapat diverifikasi. Status: ' . $payment->status_display);
        }

        DB::beginTransaction();
        try {
            // Update payment status
            $payment->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Update order status
            $payment->order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Send email to customer
            Mail::to($payment->order->customer_email)->send(new PaymentVerified($payment));

            DB::commit();

            return redirect()->route('owner.payments.show', $payment)
                ->with('success', 'Pembayaran berhasil diverifikasi! Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi pembayaran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Payment $payment)
    {
        if (!$payment->canVerify()) {
            return back()->with('error', 'Payment tidak dapat ditolak. Status: ' . $payment->status_display);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            // Send email to customer
            Mail::to($payment->order->customer_email)->send(new PaymentRejected($payment));

            DB::commit();

            return redirect()->route('owner.payments.show', $payment)
                ->with('success', 'Pembayaran ditolak. Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    public function processOrder(Request $request, Payment $payment)
    {
        if (!$payment->isVerified() || !$payment->order->canProcess()) {
            return back()->with('error', 'Order tidak dapat diproses. Status order: ' . $payment->order->status_display);
        }

        DB::beginTransaction();
        try {
            $payment->order->update([
                'status' => 'processing',
            ]);

            DB::commit();

            return redirect()->route('owner.payments.show', $payment)
                ->with('success', 'Order sedang diproses/dikemas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses order: ' . $e->getMessage());
        }
    }

    public function shipOrder(Request $request, Payment $payment)
    {
        if (!$payment->isVerified() || !$payment->order->canShip()) {
            return back()->with('error', 'Order tidak dapat dikirim. Status order: ' . $payment->order->status_display);
        }

        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'shipping_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $payment->order->update([
                'status' => 'shipped',
                'tracking_number' => $validated['tracking_number'] ?? null,
                'shipped_at' => now(),
                'admin_notes' => $validated['shipping_notes'] ?? null,
            ]);

            // Send shipping email to customer
            Mail::to($payment->order->customer_email)->send(new \App\Mail\OrderShipped($payment->order));

            DB::commit();

            return redirect()->route('owner.payments.show', $payment)
                ->with('success', 'Order dalam pengiriman! Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim order: ' . $e->getMessage());
        }
    }

    public function completeOrder(Request $request, Payment $payment)
    {
        if (!$payment->isVerified() || !$payment->order->canComplete()) {
            return back()->with('error', 'Order tidak dapat diselesaikan. Status order: ' . $payment->order->status_display);
        }

        DB::beginTransaction();
        try {
            // Update order status
            $payment->order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Send email to customer
            Mail::to($payment->order->customer_email)->send(new OrderCompleted($payment->order));

            DB::commit();

            return redirect()->route('owner.payments.show', $payment)
                ->with('success', 'Order diselesaikan! Email notifikasi telah dikirim ke customer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan order: ' . $e->getMessage());
        }
    }
}
