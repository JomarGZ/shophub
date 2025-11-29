<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;

    public function __construct(protected WishlistService $wishlistService) {}

    public function toggle(Request $request, Product $product)
    {
        $user = $request->user();

        $this->wishlistService->toggle($user, $product);

        return $this->successResponse(
            data: ['is_favorited' => $user->wishlist->contains($product)],
            message: $user->wishlist->contains($product) ? 'Product added to wishlist' : 'Product removed from wishlist',
        );
    }
}
