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
        $wishlistProducts = $this->wishlistService->getSimplePaginatedWishlistProducts(user: $user, relations: ['category:id,name']);

        return Inertia::render('favorites/index', [
            'wishlist_products' => [
                'data' => fn () => ProductResource::collection($wishlistProducts)->resolve(),
                'next_page_url' => $wishlistProducts->nextPageUrl(),
                'has_more' => $wishlistProducts->hasMorePages(),
            ],
        ]);
    }
}
