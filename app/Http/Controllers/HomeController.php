<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(protected ProductRepository $productRepository) {}

    public function __invoke()
    {
        return Inertia::render('home', [
            'featured_products' => fn () => ProductResource::collection(
                $this->productRepository->getFeaturedProducts(
                    relation: 'category:id,name',
                    columns: ['id', 'name', 'slug', 'price', 'image_url', 'category_id', 'description', 'stock'],
                    limit: 8
                )
            ),
        ]);
    }
}
