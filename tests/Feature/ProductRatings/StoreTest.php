<?php

use App\Models\Order;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Event;

beforeEach(function () {

    Event::fake();

    // Create default users
    $this->user = createUser();
    $this->otherUser = createUser();
    $this->product = createProduct();
    $this->payload = [
        'rating' => 3,
        'comment' => 'This is comment',
    ];
    $this->rateProductRoute = fn () => route('products.ratings.store', $this->product);
});

it('redirects guest when rating a product', function () {

    $response = $this->post(($this->rateProductRoute)(), $this->payload);
    $response->assertRedirect();
});

it('prevents rating without purchase', function () {
    $response = $this->actingAs($this->user)->post(($this->rateProductRoute)(), $this->payload);
    $response->assertForbidden();
});

it('allows rating purchased product', function () {
    Order::factory()->delivered()->forUser($this->user)->withProduct($this->product)->create();
    $response = $this->actingAs($this->user)->post(($this->rateProductRoute)(), $this->payload);
    $response->assertRedirect();
    $this->product->refresh();

    expect($this->product->ratings_count)->toBe(1);
    expect($this->product->ratings_sum)->toBe(3);
    expect((float) $this->product->average_rating)->toBe(3.0);
});

it('updates existing rating instead of creating a new one', function () {
    $ratedProduct = ProductRating::factory()->create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'rating' => 5,
        'comment' => 'This is old comment',
    ]);

    $this->product->update([
        'ratings_sum' => 5,
        'ratings_count' => 1,
        'average_rating' => 5.0,
    ]);
    $response = $this->actingAs($this->user)
        ->post(($this->rateProductRoute)(), $this->payload);
    $this->product->refresh();
    $ratedProduct->refresh();
    $response->assertRedirect();

    expect($ratedProduct->comment)->toBe('This is comment');
    expect($ratedProduct->rating)->toBe(3);

    expect($this->product->ratings_sum)->toBe(3);
    expect((float) $this->product->average_rating)->toBe(3.0);
});

it('fails validation for invalid rating inputs', function ($payload, $errorField) {

    Order::factory()->delivered()->forUser($this->user)->withProduct($this->product)->create();

    $response = $this->actingAs($this->user)->post(($this->rateProductRoute)(), $payload);
    $response->assertSessionHasErrors($errorField);
})->with('invalid_ratings');
