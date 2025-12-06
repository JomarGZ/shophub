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
        $page = request()->integer('page', 1);
        $perPage = request()->integer('per_page', 4);
        $user = $request->user();
        // $wishlistProducts = $this->wishlistService->getSimplePaginatedWishlistProducts(perPage: $perPage, user: $user, relations: ['category:id,name', 'wishlistedBy']);
        $wishlistProducts = $user->wishlist()->with(['category:id,name', 'wishlistedBy'])->Paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('favorites/index', [
            'wishlist_products' => Inertia::merge(ProductResource::collection($wishlistProducts))->append('data'),
        ]);
    }
}
