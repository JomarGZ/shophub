<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function __construct(protected WishlistService $wishlistService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $wishlistProducts = $this->wishlistService->getSimplePaginatedWishlistProducts(user: $user, relations: ['category:id,name', 'wishlistedBy']);

        return Inertia::render('favorites/index', [
            'wishlist_products' => Inertia::scroll(fn () => ProductResource::collection($wishlistProducts))
        ]);
    }
}
