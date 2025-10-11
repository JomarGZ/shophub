<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', ShopController::class)->name('shop');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cart', ShoppingCartController::class)->name('cart');
    Route::get('/checkout', CheckoutController::class)->name('checkout');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
