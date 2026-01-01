<?php
// File: app/Http/Controllers/Owner/PurchaseOrderApprovalController.php
// Jalankan: php artisan make:controller Owner/PurchaseOrderApprovalController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\FinancialLog;
use App\Mail\PurchaseOrderApproved;
use App\Mail\PurchaseOrderRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'creator'])
            ->orderBy('submitted_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending and approved
            $query->whereIn('status', ['pending', 'approved']);
        }

        $purchaseOrders = $query->paginate(10);

        return view('owner.purchase-orders.index', compact('purchaseOrders'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'creator', 'approver', 'items.product.category']);

        return view('owner.purchase-orders.show', compact('purchaseOrder'));
    }

    public function approve(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canApprove()) {
            return back()->with('error', 'Purchase Order tidak dapat diapprove. Status saat ini: ' . $purchaseOrder->status_display);
        }

        DB::beginTransaction();
        try {
            // Update PO status
            $purchaseOrder->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Create Financial Log
            FinancialLog::create([
                'type' => 'expense',
                'category' => 'purchase',
                'amount' => $purchaseOrder->total_amount,
                'reference_type' => 'App\Models\PurchaseOrder',
                'reference_id' => $purchaseOrder->id,
                'description' => "Purchase Order {$purchaseOrder->po_number} dari {$purchaseOrder->supplier->name}",
                'created_by' => auth()->id(),
                'transaction_date' => now()->toDateString(),
            ]);

            // Send email to admin who created the PO
            Mail::to($purchaseOrder->creator->email)->send(new PurchaseOrderApproved($purchaseOrder));

            DB::commit();

            return redirect()->route('owner.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase Order berhasil disetujui! Email notifikasi telah dikirim ke Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve Purchase Order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canApprove()) {
            return back()->with('error', 'Purchase Order tidak dapat ditolak. Status saat ini: ' . $purchaseOrder->status_display);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            // Update PO status
            $purchaseOrder->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            // Send email to admin who created the PO
            Mail::to($purchaseOrder->creator->email)->send(new PurchaseOrderRejected($purchaseOrder));

            DB::commit();

            return redirect()->route('owner.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase Order berhasil ditolak. Email notifikasi telah dikirim ke Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reject Purchase Order: ' . $e->getMessage());
        }
    }
}
