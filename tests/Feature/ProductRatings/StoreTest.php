<?php

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;

test('redirects guest when rating a product', function () {
    $product = Product::factory()->create();

    $this->post(route('products.ratings.store', $product), [
        'rating' => 5,
    ])->assertRedirect(route('login'));
});

test('allows authenticated user to rate a product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 5,
            'comment' => 'Nice!',
        ])
        ->assertRedirect();

    expect(ProductRating::count())->toBe(1);
});

test('requires rating to be between 1 and 5', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 6,
        ])
        ->assertSessionHasErrors(['rating']);
});

test('updates product summary fields on rating create', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'ratings_sum' => 0,
        'ratings_count' => 0,
        'average_rating' => 0,
    ]);

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 4,
        ]);

    $product->refresh();
    expect($product->ratings_sum)->toBe(4);
    expect($product->ratings_count)->toBe(1);
    expect((float) $product->average_rating)->toBe(4.0);
});

test('does not allow a user to create a second rating (should trigger update path)', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'ratings_sum' => 3,
        'ratings_count' => 1,
        'average_rating' => 3.0,
    ]);

    ProductRating::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 3,
    ]);

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 5,
        ])
        ->assertRedirect();

    expect(ProductRating::count())->toBe(1);
});
