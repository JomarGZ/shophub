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
    public function __invoke(Request $request, Product $product)
    {
        $user = $request->user();

        app(WishlistService::class)->toggle($user, $product);

        return $this->successResponse(
            data: ['is_favorited' => $user->wishlist->contains($product)],
            message: $user->wishlist->contains($product) ? 'Product added to wishlist' : 'Product removed from wishlist',
        );
    }
}
