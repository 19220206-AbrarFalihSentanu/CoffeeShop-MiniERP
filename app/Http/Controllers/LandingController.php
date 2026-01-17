<?php
// File: app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LandingSlide;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Setting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // Get active slides for carousel
        $slides = LandingSlide::active()->ordered()->get();

        // Get all active categories
        $categories = Category::where('is_active', true)->get();

        // Get all active products with inventory
        $products = Product::with(['category', 'inventory'])
            ->where('is_active', true)
            ->get();

        // Get active partners (kept for backward compatibility)
        $partners = Partner::active()->ordered()->get();

        // Get active promos
        $promos = Promo::active()->ordered()->get();

        // Get all landing page settings
        $settings = Setting::where('group', 'landing_page')
            ->orWhere('group', 'general')
            ->pluck('value', 'key')
            ->toArray();

        return view('landing.index', compact(
            'slides',
            'categories',
            'products',
            'partners',
            'promos',
            'settings'
        ));
    }

    /**
     * Get products by category (AJAX)
     */
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');

        $query = Product::with(['category', 'inventory'])
            ->where('is_active', true);

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => $product->price,
                    'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'image_url' => $product->image ? asset('storage/' . $product->image) : asset('assets/img/placeholder-product.png'),
                    'category' => $product->category->name ?? '-',
                    'stock' => $product->inventory->available_stock ?? 0,
                    'unit' => $product->unit ?? 'kg',
                ];
            })
        ]);
    }

    /**
     * Show product detail modal (AJAX)
     */
    public function getProductDetail($id)
    {
        $product = Product::with(['category', 'inventory'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'image_url' => $product->image ? asset('storage/' . $product->image) : asset('assets/img/placeholder-product.png'),
                'category' => $product->category->name ?? '-',
                'type' => $product->type ?? '-',
                'weight' => $product->weight,
                'unit' => $product->unit ?? 'kg',
                'min_order_qty' => $product->min_order_qty,
                'stock' => $product->inventory->available_stock ?? 0,
            ]
        ]);
    }

    /**
     * Show shipping tracking page
     */
    public function tracking()
    {
        // Get settings for the page
        $settings = Setting::where('group', 'landing_page')
            ->orWhere('group', 'general')
            ->pluck('value', 'key')
            ->toArray();

        return view('landing.tracking', compact('settings'));
    }
}
