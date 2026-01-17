<?php
// File: app/Http/Controllers/Admin/PurchaseOrderController.php
// Jalankan: php artisan make:controller Admin/PurchaseOrderController --resource

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Mail\PurchaseOrderSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $purchaseOrders = $query->paginate(10);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::with('category')->active()->orderBy('name')->get();

        return view('admin.purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create PO
            $po = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'created_by' => auth()->id(),
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'notes' => $validated['notes'],
                'status' => 'draft',
            ]);

            // Create PO Items
            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.purchase-orders.show', $po)
                ->with('success', 'Purchase Order berhasil dibuat dengan nomor: ' . $po->po_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat Purchase Order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'creator', 'approver', 'items.product.category']);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canEdit()) {
            return back()->with('error', 'Purchase Order tidak dapat diedit karena statusnya: ' . $purchaseOrder->status_display);
        }

        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::with('category')->active()->orderBy('name')->get();
        $purchaseOrder->load(['items.product']);

        return view('admin.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Validasi: Hanya bisa edit jika draft atau rejected
        if (!$purchaseOrder->canEdit()) {
            return back()->with(
                'error',
                'Purchase Order tidak dapat diedit karena statusnya: ' .
                    $purchaseOrder->status_display
            );
        }

        // Simpan status sebelumnya untuk pesan
        $isRejected = $purchaseOrder->isRejected();

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update PO
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'notes' => $validated['notes'],
            ]);

            // Delete old items
            $purchaseOrder->items()->delete();

            // Create new items
            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            DB::commit();

            // Different message for rejected vs draft
            if ($isRejected) {
                $message = 'Purchase Order berhasil diupdate! Anda dapat submit ulang untuk approval.';
            } else {
                $message = 'Purchase Order berhasil diupdate!';
            }

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal mengupdate Purchase Order: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->canDelete()) {
            return back()->with('error', 'Purchase Order tidak dapat dihapus karena statusnya: ' . $purchaseOrder->status_display);
        }

        $poNumber = $purchaseOrder->po_number;
        $purchaseOrder->delete();

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', "Purchase Order {$poNumber} berhasil dihapus!");
    }

    public function submit(PurchaseOrder $purchaseOrder)
    {
        // Validasi: Hanya draft atau rejected yang bisa di-submit
        if (!$purchaseOrder->canSubmit()) {
            $currentStatus = $purchaseOrder->status_display;
            return back()->with(
                'error',
                "Purchase Order tidak dapat disubmit. Status saat ini: {$currentStatus}. " .
                    "Hanya PO dengan status Draft atau Rejected yang dapat disubmit."
            );
        }

        // Simpan status sebelumnya untuk pesan
        $wasRejected = $purchaseOrder->isRejected();

        DB::beginTransaction();
        try {
            // PENTING: Reset approval data jika re-submit
            $updateData = [
                'status' => 'pending',
                'submitted_at' => now(),
            ];

            // Jika ini re-submit setelah rejected, clear rejection data
            if ($wasRejected) {
                $updateData['approved_by'] = null;
                $updateData['rejected_at'] = null;
                $updateData['rejection_reason'] = null;
            }

            $purchaseOrder->update($updateData);

            // Get all owners
            $owners = User::where('role_id', 1)->get(); // role_id 1 = Owner

            // Send email to all owners
            foreach ($owners as $owner) {
                Mail::to($owner->email)->queue(new PurchaseOrderSubmitted($purchaseOrder));
            }

            DB::commit();

            // Different message for re-submit vs first submit
            $message = $wasRejected
                ? 'Purchase Order berhasil disubmit ulang untuk approval. Email notifikasi telah dikirim ke Owner.'
                : 'Purchase Order berhasil disubmit untuk approval. Email notifikasi telah dikirim ke Owner.';

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal submit Purchase Order: ' . $e->getMessage());
        }
    }
}
