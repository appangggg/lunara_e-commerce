<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserOrderController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;

// LUNARA Storefront Routes
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/collections', function() {
    $collections = \App\Models\Collection::all();
    return view('collections.index', compact('collections'));
})->name('collections.index');
Route::get('/drops', function() {
    $drops = \App\Models\Drop::orderBy('date_label', 'asc')->get();
    return view('drops.index', compact('drops'));
})->name('drops.index');

// Cart Routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add-drop', [CartController::class, 'addDrop'])->name('cart.add-drop');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Webhook Route
Route::post('/api/webhook/midtrans', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])->name('api.webhook.midtrans');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

// Protected Customer Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Orders
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders');

    // Checkout
    Route::post('/checkout/init', [CheckoutController::class, 'init'])->name('checkout.init');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product CRUD
    Route::get('/products/create', [AdminController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('products.destroy');

    // Additional Resources
    Route::resource('collections', \App\Http\Controllers\Admin\CollectionController::class)->except(['index', 'show']);
    Route::resource('drops', \App\Http\Controllers\Admin\DropController::class)->except(['index', 'show']);
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
