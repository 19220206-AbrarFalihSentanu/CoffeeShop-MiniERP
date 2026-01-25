<?php
// File: app/Http/Controllers/Admin/PurchaseOrderHistoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderHistoryController extends Controller
{
    /**
     * Display all purchase orders with history (all statuses)
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'items.product', 'creator', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by PO number or supplier name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->input('per_page', 15);
        $purchaseOrders = $query->paginate($perPage)->withQueryString();

        // Get statistics
        $stats = [
            'total' => PurchaseOrder::count(),
            'completed' => PurchaseOrder::where('status', 'received')->count(),
            'pending' => PurchaseOrder::where('status', 'pending')->count(),
            'approved' => PurchaseOrder::where('status', 'approved')->count(),
            'rejected' => PurchaseOrder::where('status', 'rejected')->count(),
        ];

        return view('admin.purchase-orders.history.index', compact('purchaseOrders', 'stats'));
    }

    /**
     * Show purchase order detail
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product', 'creator', 'approver']);

        return view('admin.purchase-orders.history.show', compact('purchaseOrder'));
    }
}
