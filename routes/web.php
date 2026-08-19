<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FlutterwaveWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{slug}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/faqs', [PageController::class, 'faqs'])->name('page.faqs');
Route::get('/contact', [PageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('page.contact.submit');

Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('account.orders');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
Route::post('/webhooks/flutterwave', [FlutterwaveWebhookController::class, 'handle'])->name('webhooks.flutterwave');

Route::get('/documentation', function () {
    return view('documentation');
})->middleware(['auth', 'admin'])->name('documentation');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');

    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('categories');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');

    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderExportController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'download'])->name('orders.invoice');

    Route::get('/coupons', function () {
        return view('admin.coupons');
    })->name('coupons');

    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');

    Route::get('/customers', function () {
        return view('admin.customers');
    })->name('customers');

    Route::get('/slides', function () {
        return view('admin.slides');
    })->name('slides');

    Route::get('/faqs', function () {
        return view('admin.faqs');
    })->name('faqs');
});

require __DIR__.'/auth.php';
