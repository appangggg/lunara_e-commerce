<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Set Midtrans Configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        
        if ($request->has('order_id') && $request->has('transaction_status')) {
            // Fallback for simulated local webhooks
            $transactionStatus = $request->transaction_status;
            $orderId = $request->order_id;
            $fraudStatus = $request->fraud_status ?? 'accept';
        } else {
            try {
                $notification = new Notification();
                $transactionStatus = $notification->transaction_status;
                $orderId = $notification->order_id;
                $fraudStatus = $notification->fraud_status;
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }

        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->update(['status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $order->update(['status' => 'paid']);
            }
        } else if ($transactionStatus == 'settlement') {
            $order->update(['status' => 'paid']);
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->update(['status' => 'expired']);
            // Return stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        } else if ($transactionStatus == 'pending') {
            $order->update(['status' => 'pending']);
        }

        return response()->json(['status' => 'success']);
    }
}
