<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WishlistToggleController extends Controller
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Product $product,
        WishlistService $wishlistService
    ) {
        $wishlistService->toggle(auth()->id(), $product->id);

        return redirect()->route('wishlist.index');
    }
}
