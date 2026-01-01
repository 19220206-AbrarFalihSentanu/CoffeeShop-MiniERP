<?php
// File: app/Http/Controllers/CatalogController.php
// Jalankan: php artisan make:controller CatalogController

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display product catalog
     * Accessible by: Owner, Admin, Customer
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory'])
            ->where('is_active', true)
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) > 0'); // Only products with stock
            });

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by discount/sale
        if ($request->has('sale') && $request->sale == '1') {
            $query->onSale();
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                // TODO: Order by sales count when order system is ready
                $query->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        // Get categories for filter
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->having('products_count', '>', 0)
            ->get();

        // Featured products for homepage
        $featuredProducts = Product::with(['category', 'inventory'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) > 0');
            })
            ->take(4)
            ->get();

        // Products on sale
        $saleProducts = Product::with(['category', 'inventory'])
            ->where('is_active', true)
            ->onSale()
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) > 0');
            })
            ->take(4)
            ->get();

        return view('catalog.index', compact(
            'products',
            'categories',
            'featuredProducts',
            'saleProducts'
        ));
    }

    /**
     * Display single product detail
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'inventory'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Related products (same category)
        $relatedProducts = Product::with(['category', 'inventory'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->whereHas('inventory', function ($q) {
                $q->whereRaw('(quantity - reserved) > 0');
            })
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    /**
     * Quick view product (for modal/AJAX)
     */
    public function quickView(Product $product)
    {
        $product->load(['category', 'inventory']);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'description' => $product->description,
                'type' => $product->type,
                'weight' => $product->weight,
                'price' => $product->price,
                'final_price' => $product->final_price,
                'discount_percentage' => $product->discount_percentage,
                'savings_amount' => $product->savings_amount,
                'is_discount_active' => $product->isDiscountActive(),
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'category' => $product->category->name,
                'available_stock' => $product->getAvailableStock(),
                'in_stock' => $product->hasStock(),
            ]
        ]);
    }
}
