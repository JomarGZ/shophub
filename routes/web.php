<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Nnjeim\World\Models\City;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/cart', CartController::class)->only(['store', 'index']);
    Route::delete('cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.item.destroy');
    Route::patch('cart/{cartItem}', [CartController::class, 'update'])->name('cart.item.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::post('checkout/address', [AddressController::class, 'store'])->name('address.store');
});

Route::get('city/list', [CityController::class, 'index']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
