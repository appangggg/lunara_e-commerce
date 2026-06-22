<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Get all products, order by ID descending
        $products = Product::all();
        $settings = \App\Models\Setting::pluck('value', 'key');
        return view('products.index', compact('products', 'settings'));
    }

    public function show($id)
    {
        // Using findOrFail will automatically throw a 404 if the product doesn't exist
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}
