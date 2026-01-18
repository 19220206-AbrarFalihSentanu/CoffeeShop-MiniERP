<?php

// ============================================================
// File: app/Http/Controllers/Owner/SupplierController.php
// ============================================================

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->byType($request->type);
        }

        $perPage = $request->input('per_page', 10);
        $suppliers = $query->latest()->paginate($perPage)->withQueryString();

        return view('owner.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create()
    {
        return view('owner.suppliers.create');
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:suppliers'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'type' => ['required', 'in:petani,distributor,koperasi'],
            'notes' => ['nullable', 'string'],
        ]);

        // Generate supplier code
        $lastSupplier = Supplier::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastSupplier ? ((int) substr($lastSupplier->code, 4)) + 1 : 1;
        $validated['code'] = 'SUP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $validated['is_active'] = true;

        Supplier::create($validated);

        return redirect()->route('owner.suppliers.index')
            ->with('success', __('suppliers.supplier_created'));
    }

    /**
     * Display the specified supplier
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('purchaseOrders');
        return view('owner.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier)
    {
        return view('owner.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:suppliers,email,' . $supplier->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'type' => ['required', 'in:petani,distributor,koperasi'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $supplier->update($validated);

        return redirect()->route('owner.suppliers.index')
            ->with('success', __('suppliers.supplier_updated'));
    }

    /**
     * Remove the specified supplier
     */
    public function destroy(Supplier $supplier)
    {
        // Check if supplier has purchase orders
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', __('suppliers.cannot_delete_has_po'));
        }

        $supplier->delete();

        return redirect()->route('owner.suppliers.index')
            ->with('success', __('suppliers.supplier_deleted'));
    }

    /**
     * Toggle supplier active status
     */
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update([
            'is_active' => !$supplier->is_active
        ]);

        $status = $supplier->is_active ? __('general.enabled') : __('general.disabled');

        return back()->with('success', __('suppliers.supplier_status_changed', ['status' => $status]));
    }
}

