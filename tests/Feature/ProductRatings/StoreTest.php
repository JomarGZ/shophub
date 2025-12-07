<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

dataset('invalid_ratings', [
    [['rating' => 0], 'rating'],
    [['rating' => null], 'rating'],
    [['rating' => 6], 'rating'],
    [['rating' => []], 'rating'],
    [['rating' => 'abs'], 'rating'],
    [['comment' => Str::repeat('a', 501)], 'comment'],
    [['comment' => 123], 'comment'],
    [['comment' => []], 'comment'],
]);
test('redirects guest when rating a product', function () {
    $product = Product::factory()->create();

    $this->post(route('products.ratings.store', $product), [
        'rating' => 5,
    ])->assertRedirect(route('login'));
});

test('allows authenticated user to rate a product', function () {
    Event::fake();
    $user = User::factory()->create();
    $product = Product::factory()->create();
    Order::factory()
        ->delivered()
        ->forUser($user)
        ->withProduct($product)
        ->create();
    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 5,
            'comment' => 'Nice!',
        ])
        ->assertRedirect();

    expect(ProductRating::count())->toBe(1);
});

test('fails validation for invalid rating inputs', function ($payload, $errorField) {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), $payload)
        ->assertSessionHasErrors($errorField);
})->with('invalid_ratings');

test('updates product summary fields on rating create', function () {
    Event::fake();
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'ratings_sum' => 0,
        'ratings_count' => 0,
        'average_rating' => 0,
    ]);
    Order::factory()
        ->delivered()
        ->forUser($user)
        ->withProduct($product)
        ->create();

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

//Ensure only ordered users can rate products
test('prevents users who have not ordered the product from rating it', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 4
        ])
        ->assertStatus(403);
});

test('allows user who ordered the product to rate it', function () {
    Event::fake();
    $user = User::factory()->create();
    $product = Product::factory()->create();

    Order::factory()
        ->delivered()
        ->forUser($user)
        ->withProduct($product)
        ->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), [
            'rating' => 5,
        ])
        ->assertRedirect();

    expect(ProductRating::count())->toBe(1);
});

