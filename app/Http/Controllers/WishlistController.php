<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $wishlistProducts = $this->productRepository->paginateWishlistProducts(userId: $user->id);
        return Inertia::render('favorites/index', [
            'wishlist_products' => ProductResource::collection($wishlistProducts),
        ]);
    }

    
}
