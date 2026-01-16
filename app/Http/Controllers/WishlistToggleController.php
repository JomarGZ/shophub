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

        $favorite = app(WishlistService::class)->toggle($user, $product);
        $message = ! empty($favorite['attached'])
            ? 'Product added to wishlist.'
            : 'Product removed from wishlist.';

        return redirect()->back()->with('success', $message);
    }
}
