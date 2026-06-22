<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index()
    {
        // Get all orders for the currently authenticated user
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }
}
