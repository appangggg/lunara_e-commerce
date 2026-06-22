<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Midtrans Webhook (API endpoint to avoid CSRF validation)
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle'])->name('api.webhook.midtrans');
