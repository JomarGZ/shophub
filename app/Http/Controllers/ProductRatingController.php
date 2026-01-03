<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRatingRequest;
use App\Http\Requests\UpdateProductRatingRequest;
use App\Models\Product;
use App\Models\ProductRating;
use App\Services\ProductRating\ProductRatingService;

class ProductRatingController extends Controller
{
    public function __construct(protected ProductRatingService $productRatingService) {}

    public function store(StoreProductRatingRequest $request, Product $product)
    {
        $this->productRatingService->rateProduct(
            $request->user(),
            $product,
            $request->integer('rating'),
            $request->string('comment'),
        );

        return redirect()->back();
    }

    public function update(UpdateProductRatingRequest $request, ProductRating $productRating)
    {
        $this->productRatingService->rateProduct(
            $request->user(),
            $productRating->product,
            $request->integer('rating'),
            $request->string('comment')->trim(),
        );

        return redirect()->back();
    }
}
