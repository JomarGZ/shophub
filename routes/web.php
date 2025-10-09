<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', ShopController::class)->name('shop');
Route::middleware(['auth', 'verified'])->group(function () {});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
