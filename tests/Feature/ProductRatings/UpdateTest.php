<?php

use App\Models\Order;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Event;

beforeEach(function () {

    Event::fake();

    $this->user = createUser();
    $this->otherUser = createUser();
    $this->product = createProduct();
    $this->payload = [
        'rating' => 3,
        'comment' => 'This is comment',
    ];
    Order::factory()->delivered()->forUser($this->user)->withProduct($this->product)->create();

    $this->productRating = ProductRating::factory()->create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'rating' => 5,
        'comment' => 'Old comment',
    ]);

    $this->product->update([
        'ratings_sum' => 5,
        'ratings_count' => 1,
        'average_rating' => 5.0,
    ]);

    $this->updateRatedProductRoute = fn () => route('products.ratings.update', $this->productRating);
});

it('redirects guest when updating a product rating', function () {
    $this->put(($this->updateRatedProductRoute)(), $this->payload)
        ->assertRedirect();
});

it('allows user to update own', function () {
    $response = $this->actingAs($this->user)->put(($this->updateRatedProductRoute)(), $this->payload);
    $this->productRating->refresh();
    $response->assertRedirect();
    expect($this->productRating->rating)->toBe(3);
});

// fails validation for invalid update inputs
it('fails validation for invalid rating inputs', function ($payload, $errorField) {
    $response = $this->actingAs($this->user)->put(($this->updateRatedProductRoute)(), $payload);
    $response->assertSessionHasErrors($errorField);
})->with('invalid_ratings');
