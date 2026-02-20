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
        $added = $wishlistService->toggle(request()->user()?->id, $product->id);
        
        return redirect()->route('wishlist.index')->with('success', $added ? "Product added to wishlist" : "Product removed from wishlist");
    }
}
