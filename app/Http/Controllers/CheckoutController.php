<?php
// File: app/Http/Controllers/CheckoutController.php
// Jalankan: php artisan make:controller CheckoutController

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $user = auth()->user();

        // Get cart items
        $cartItems = Cart::with(['product.category', 'product.inventory'])
            ->where('user_id', $user->id)
            ->available()
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Check stock availability for all items
        foreach ($cartItems as $item) {
            if (!$item->isAvailable()) {
                return redirect()->route('customer.index')
                    ->with('error', "Produk {$item->product->name} tidak tersedia. Silakan hapus dari keranjang.");
            }
        }

        // Calculate totals
        $subtotal = $cartItems->sum('subtotal');
        $taxRate = (float) setting('tax_rate', 11);
        $tax = $subtotal * ($taxRate / 100);
        $shipping = (float) setting('shipping_cost', 25000);
        $total = $subtotal + $tax + $shipping;

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'taxRate',
            'shipping',
            'total'
        ));
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = auth()->user();

        // Get cart items
        $cartItems = Cart::with(['product.inventory'])
            ->where('user_id', $user->id)
            ->available()
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');
            $taxRate = (float) setting('tax_rate', 11);
            $tax = $subtotal * ($taxRate / 100);
            $shipping = (float) setting('shipping_cost', 25000);
            $total = $subtotal + $tax + $shipping;

            // Create Order
            $order = Order::create([
                'customer_id' => $user->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $tax,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
                'customer_notes' => $validated['customer_notes'],
            ]);

            // Create Order Items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'product_weight' => $cartItem->product->weight,
                    'product_type' => $cartItem->product->type,
                    'quantity' => $cartItem->quantity,
                    'unit' => $cartItem->product->unit ?? 'kg',
                    'unit_price' => $cartItem->price,
                ]);

                // Reserve stock
                $cartItem->product->inventory->reserveStock($cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // TODO: Send email notifications
            // Mail::to($order->customer_email)->send(new OrderCreatedMail($order));
            // Mail::to(owner email)->send(new NewOrderNotification($order));

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Order Number: ' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}
