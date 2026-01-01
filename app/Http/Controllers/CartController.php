<?php
// File: app/Http/Controllers/CartController.php
// Jalankan: php artisan make:controller CartController

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display shopping cart
     */
    public function index()
    {
        $cartItems = Cart::with(['product.category', 'product.inventory'])
            ->where('user_id', auth()->id())
            ->get();

        // Calculate totals
        $subtotal = $cartItems->sum('subtotal');

        // Get tax rate from settings
        $taxRate = (float) setting('tax_rate', 11);
        $tax = $subtotal * ($taxRate / 100);

        // Get shipping cost from settings
        $shipping = (float) setting('shipping_cost', 25000);

        $total = $subtotal + $tax + $shipping;

        // Check for unavailable items
        $unavailableItems = $cartItems->filter(function ($item) {
            return !$item->isAvailable();
        });

        // Check for price changes
        $priceChangedItems = $cartItems->filter(function ($item) {
            return $item->hasPriceChanged();
        });

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'taxRate',
            'shipping',
            'total',
            'unavailableItems',
            'priceChangedItems'
        ));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        // Check if product is active
        if (!$product->is_active) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        // Check stock availability
        if (!$product->hasStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->getAvailableStock());
        }

        try {
            DB::beginTransaction();

            // Check if item already in cart
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $validated['quantity'];

                // Check stock for new quantity
                if (!$product->hasStock($newQuantity)) {
                    DB::rollBack();
                    return back()->with('error', 'Tidak dapat menambah. Stok maksimal: ' . $product->getAvailableStock());
                }

                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->final_price // Update to current price
                ]);

                $message = 'Jumlah produk di keranjang berhasil diperbarui!';
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->final_price
                ]);

                $message = 'Produk berhasil ditambahkan ke keranjang!';
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan ke keranjang: ' . $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        // Check stock availability
        if (!$cart->product->hasStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi untuk produk ' . $cart->product->name . '. Stok tersedia: ' . $cart->product->getAvailableStock());
        }

        try {
            $cart->update([
                'quantity' => $validated['quantity'],
                'price' => $cart->product->final_price // Update to current price
            ]);

            return back()->with('success', 'Jumlah produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui keranjang: ' . $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cart)
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $productName = $cart->product->name;
            $cart->delete();

            return back()->with('success', "Produk \"{$productName}\" berhasil dihapus dari keranjang!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        try {
            Cart::where('user_id', auth()->id())->delete();

            return back()->with('success', 'Keranjang berhasil dikosongkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengosongkan keranjang: ' . $e->getMessage());
        }
    }

    /**
     * Update all prices to current product prices
     */
    public function updatePrices()
    {
        try {
            $cartItems = Cart::where('user_id', auth()->id())->get();

            foreach ($cartItems as $item) {
                if ($item->product) {
                    $item->update([
                        'price' => $item->product->final_price
                    ]);
                }
            }

            return back()->with('success', 'Harga produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui harga: ' . $e->getMessage());
        }
    }

    /**
     * Get cart count (for AJAX/API)
     */
    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
