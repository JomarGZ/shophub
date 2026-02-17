<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    public function index()
    {
        return Inertia::render('shop/index', [
            'filters' => Request::only('search', 'categories', 'min_price', 'max_price'),
            'price_range' => fn () => $this->productRepository->getPriceRange(
                Request::only('search', 'categories')
            ),
            'products' => fn () => ProductResource::collection(
                $this->productRepository->paginateWithWishlist(12, request()->user()?->id),
            ),
            'categories' => fn () => CategoryResource::collection($this->categoryRepository->getOnlyWithProducts()),
            'focus' => Request::get('focus'),
        ]);
    }

    public function show(string $slug)
    {
        $product = $this->productRepository->findWithWishlistBySlug($slug, request()->user()?->id);

        return Inertia::render('shop/show', [
            'product' => ProductResource::make($product->load(['category:id,name'])),
            'related_products' => ProductResource::collection($this->productRepository->getRelatedProducts(
                catId: $product->category_id,
                columns: ['id', 'name', 'slug', 'price', 'image_url', 'category_id', 'description', 'stock', 'average_rating', 'ratings_count'],
                limit: 4
            )),
        ]);
    }
}
