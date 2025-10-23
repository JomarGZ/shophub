<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(protected ProductRepository $productRepo) {}

    public function __invoke()
    {
        return Inertia::render('home', [
            'featured_products' => fn () => ProductResource::collection(
                $this->productRepo->getFeaturedProducts(relation: ['category:id,name'], limit: 8)
            ),
        ]);
    }
}
