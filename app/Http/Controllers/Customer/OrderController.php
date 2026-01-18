<?php
// File: app/Http/Controllers/Customer/OrderController.php (UPDATE)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Http\Requests\UploadPaymentRequest;
use App\Mail\PaymentProofUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'approver', 'payment'])
            ->forCustomer(auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['items.product', 'approver', 'payment']);

        return view('customer.orders.show', compact('order'));
    }

    public function uploadPayment(UploadPaymentRequest $request, Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canUploadPayment() && !$order->canReuploadPayment()) {
            return back()->with('error', 'Order ini tidak dapat melakukan pembayaran. Status: ' . $order->status_display);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Check if reupload (payment rejected)
            if ($order->hasPayment() && $order->payment->isRejected()) {
                $existingPayment = $order->payment;

                // Delete old proof
                if ($existingPayment->payment_proof) {
                    Storage::disk('public')->delete($existingPayment->payment_proof);
                }

                // Upload new proof
                $path = $request->file('payment_proof')->store('payments', 'public');

                // Update existing payment
                $existingPayment->update([
                    'payment_method' => $validated['payment_method'],
                    'payment_proof' => $path,
                    'customer_notes' => $validated['customer_notes'] ?? null,
                    'status' => 'pending',
                    'rejection_reason' => null,
                    'rejected_at' => null,
                ]);

                $payment = $existingPayment;
            } else {
                // Upload payment proof
                $path = $request->file('payment_proof')->store('payments', 'public');

                // Create new payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'payment_method' => $validated['payment_method'],
                    'payment_proof' => $path,
                    'customer_notes' => $validated['customer_notes'] ?? null,
                    'status' => 'pending',
                ]);
            }

            // Send email to Admin & Owner
            $admins = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['admin', 'owner']);
            })->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new PaymentProofUploaded($payment));
            }

            DB::commit();

            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi Admin/Owner.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isPending()) {
            return back()->with('error', 'Order tidak dapat dibatalkan. Status: ' . $order->status_display);
        }

        try {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->inventory) {
                    $item->product->inventory->releaseReserved($item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            return back()->with('success', 'Order berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }

    public function confirmReceived(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        if (!$order->canComplete()) {
            return back()->with('error', 'Pesanan tidak dapat dikonfirmasi. Status: ' . $order->status_display);
        }

        try {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return back()->with('success', 'Pesanan telah dikonfirmasi selesai. Terima kasih telah berbelanja!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengkonfirmasi pesanan: ' . $e->getMessage());
        }
    }
}

