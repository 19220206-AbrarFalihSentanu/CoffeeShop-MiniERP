<?php
// File: app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status === 'low') {
                $query->whereHas('inventory', function ($q) {
                    $q->whereRaw('quantity - reserved <= products.min_stock');
                });
            } elseif ($request->stock_status === 'out') {
                $query->whereHas('inventory', function ($q) {
                    $q->whereRaw('quantity - reserved <= 0');
                });
            }
        }

        // Search by name or SKU
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        // Fix validasi - has_discount harus boolean dulu
        $request->merge([
            'has_discount' => $request->has('has_discount') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:whole_bean,ground,instant'],
            'weight' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:gram,kg,ton'],
            'min_order_qty' => ['nullable', 'numeric', 'min:0'],
            'order_increment' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],

            // FIX: Diskon hanya required jika has_discount = true
            'has_discount' => ['boolean'],
            'discount_type' => ['required_if:has_discount,true', 'nullable', 'in:percentage,fixed'],
            'discount_value' => ['required_if:has_discount,true', 'nullable', 'numeric', 'min:0'],
            'discount_start_date' => ['nullable', 'date'],
            'discount_end_date' => ['nullable', 'date', 'after_or_equal:discount_start_date'],

            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'initial_stock' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean']
        ]);

        // FIX: Jika tidak ada diskon, set null untuk field diskon
        if (!$validated['has_discount']) {
            $validated['discount_type'] = null;
            $validated['discount_value'] = null;
            $validated['discount_start_date'] = null;
            $validated['discount_end_date'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Auto-generate slug dan SKU
        $validated['slug'] = Str::slug($validated['name']);
        $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));

        // Create product
        $product = Product::create($validated);

        // Create inventory dengan initial_stock
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $validated['initial_stock'],
            'reserved' => 0
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'inventory', 'inventoryLogs.user']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('inventory');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        // Fix validasi - convert checkbox ke boolean
        $request->merge([
            'has_discount' => $request->has('has_discount') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
        ]);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:whole_bean,ground,instant'],
            'weight' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:gram,kg,ton'],
            'min_order_qty' => ['nullable', 'numeric', 'min:0'],
            'order_increment' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],

            // FIX: Diskon hanya required jika has_discount = true
            'has_discount' => ['boolean'],
            'discount_type' => ['required_if:has_discount,true', 'nullable', 'in:percentage,fixed'],
            'discount_value' => ['required_if:has_discount,true', 'nullable', 'numeric', 'min:0'],
            'discount_start_date' => ['nullable', 'date'],
            'discount_end_date' => ['nullable', 'date', 'after_or_equal:discount_start_date'],

            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean']
        ]);

        // FIX: Jika tidak ada diskon, set null
        if (!$validated['has_discount']) {
            $validated['discount_type'] = null;
            $validated['discount_value'] = null;
            $validated['discount_start_date'] = null;
            $validated['discount_end_date'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Soft delete - data masih ada tapi tidak tampil
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk berhasil {$status}!");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update([
            'is_featured' => !$product->is_featured
        ]);

        $status = $product->is_featured ? 'ditandai sebagai featured' : 'dihapus dari featured';

        return back()->with('success', "Produk berhasil {$status}!");
    }
}
