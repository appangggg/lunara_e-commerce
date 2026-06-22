<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    /**
     * Set session for Buy Now action or proceed from cart
     */
    public function init(Request $request)
    {
        if ($request->has('cart_checkout')) {
            // Processing from multi-item cart
            if (empty(session('cart'))) {
                return back()->with('error', 'Your cart is empty.');
            }
            session()->put('checkout_source', 'cart');
        } else {
            // Processing from Buy Now
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Insufficient stock.');
            }

            session()->put('checkout_cart', [
                $product->id => [
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'name' => $product->name,
                    'image_url' => $product->image_url
                ]
            ]);
            session()->put('checkout_source', 'direct');
        }

        return redirect()->route('checkout.index');
    }

    public function index()
    {
        $source = session('checkout_source');
        $cart = $source === 'cart' ? session('cart', []) : session('checkout_cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Checkout cart is empty.');
        }

        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $source = session('checkout_source');
        $cart = $source === 'cart' ? session('cart', []) : session('checkout_cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        // Validate request
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $totalPrice = 0;
        $itemDetails = [];

        // Verify stock and prepare details
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                return response()->json(['error' => 'Product ' . $item['name'] . ' unavailable or out of stock.'], 400);
            }
            $totalPrice += $item['price'] * $item['quantity'];
            $itemDetails[] = [
                'id' => $product->id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name'],
            ];
        }

        $orderNumber = 'LUNARA-' . strtoupper(Str::random(8));

        // Create Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $orderNumber,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'customer_email' => $request->email,
            'shipping_address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ]);

        // Create Order Items and decrease stock
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            
            // Decrease stock temporarily (will be finalized on webhook, but good practice here)
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }

        // Prepare Midtrans Params
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('user.orders')
            ]
        ];

        try {
            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Save Token to Order
            $order->update(['snap_token' => $snapToken]);

            // Clear session cart
            if ($source === 'cart') {
                session()->forget('cart');
            } else {
                session()->forget('checkout_cart');
            }
            session()->forget('checkout_source');

            // Return JSON for frontend
            return response()->json(['snap_token' => $snapToken]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
