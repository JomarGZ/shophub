<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductRating;


dataset('invalid_ratings', [
    [['rating' => 0], 'rating'],
    [['rating' => null], 'rating'],
    [['rating' => 6], 'rating'],
    [['rating' => []], 'rating'],
    [['rating' => 'abs'], 'rating'],
    [['comment' => Illuminate\Support\Str::repeat('a', 501)], 'comment'],
    [['comment' => 123], 'comment'],
    [['comment' => []], 'comment'],
]);


function createOrderedProductForUser(User $user, array $productAttributes = [], ?int $rating = null): Product {
    $product = Product::factory()->create($productAttributes);

    if ($rating) {
        ProductRating::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $rating,
        ]);
    }

    Order::factory()->delivered()->forUser($user)->withProduct($product)->create();

    return $product;
}

function createUser(): App\Models\User {
    return App\Models\User::factory()->create();
}