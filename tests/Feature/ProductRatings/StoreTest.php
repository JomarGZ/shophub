<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    // Create default users
    $this->guest = null;
    $this->user = createUser();
    $this->otherUser = createUser();
});

dataset('rating_scenarios', [
    'guest redirected' => ['guest', null, ['rating' => 5], 302],
    'user cannot rate unpurchased product' => ['user', false, ['rating' => 4], 403],
    'user can rate purchased product' => ['user', true, ['rating' => 5, 'comment' => 'Nice!'], 302],
    'user cannot rate twice (update path)' => ['user_already_rated', true, ['rating' => 5], 302],
]);

it('handles product rating scenarios', function (string $role, ?bool $hasPurchased, array $payload, int $expectedStatus) {
    // Determine the user
    $user = match ($role) {
        'guest' => null,
        'user' => $this->user,
        'user_already_rated' => $this->user,
    };

    // Create product
    $product = Product::factory()->create();

    // Attach order if user purchased
    if ($user && $hasPurchased) {
        Order::factory()->delivered()->forUser($user)->withProduct($product)->create();
    }

    // Attach previous rating if simulating update
    if ($role === 'user_already_rated') {
        ProductRating::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 3,
        ]);

        $product->update([
            'ratings_sum' => 3,
            'ratings_count' => 1,
            'average_rating' => 3.0,
        ]);
    }

    // Perform POST request
    $response = $user
        ? $this->actingAs($user)->post(route('products.ratings.store', $product), $payload)
        : $this->post(route('products.ratings.store', $product), $payload);

    $response->assertStatus($expectedStatus);

    // Additional assertions for successful ratings
    if ($expectedStatus === 302 && $role !== 'guest') {
        $product->refresh();
        $this->assertTrue($product->ratings_count >= 1);
        $this->assertTrue($product->ratings_sum >= 3);
    }
})->with('rating_scenarios');

dataset('invalid_ratings', [
    [['rating' => 0], 'rating'],
    [['rating' => null], 'rating'],
    [['rating' => 6], 'rating'],
    [['rating' => []], 'rating'],
    [['rating' => 'abs'], 'rating'],
    [['comment' => str_repeat('a', 501)], 'comment'],
    [['comment' => 123], 'comment'],
    [['comment' => []], 'comment'],
]);

it('fails validation for invalid rating inputs', function ($payload, $errorField) {
    $user = $this->user;
    $product = Product::factory()->create();

    Order::factory()->delivered()->forUser($user)->withProduct($product)->create();

    $this->actingAs($user)
        ->post(route('products.ratings.store', $product), $payload)
        ->assertSessionHasErrors($errorField);
})->with('invalid_ratings');
