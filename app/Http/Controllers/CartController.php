<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image_url' => $product->image_url
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart!');
    }

    public function addDrop(Request $request)
    {
        $request->validate([
            'drop_id' => 'required|exists:drops,id',
        ]);

        $drop = \App\Models\Drop::findOrFail($request->drop_id);
        
        $cart = session()->get('cart', []);
        
        // Use a string ID for drops to avoid collision with products
        $cartId = 'drop_' . $drop->id;

        // Parse price from label (e.g. "Rp 1.250.000" -> 1250000)
        $priceStr = preg_replace('/[^0-9]/', '', $drop->price_label);
        $price = $priceStr ? (int) $priceStr : 0;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += 1;
        } else {
            $cart[$cartId] = [
                'product_id' => $cartId,
                'name' => $drop->name . ' (Drop)',
                'price' => $price,
                'quantity' => 1,
                'image_url' => $drop->image_url
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Drop added to cart!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $cart = session()->get('cart');

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart!');
    }
}
