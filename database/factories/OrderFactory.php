<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 500);
        $shippingFee = $this->faker->randomFloat(2, 10, 50);
        $discount = $this->faker->randomFloat(2, 0, 20);
        $total = $subtotal + $shippingFee - $discount;

        return [
            'user_id' => User::factory(),
            'address_id' => null,
            'status' => OrderStatus::PENDING,
            'payment_status' => 'pending', // or use PaymentStatus enum if defined
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => PaymentMethod::STRIPE, // or random method
            'transaction_id' => null,
            'external_reference' => null,
            'payment_metadata' => null,
            'paid_at' => null,
            'refund_amount' => 0,
            'refund_at' => null,
            'rejection_reason' => null,
            'shipping_full_name' => fake()->name(),
            'shipping_phone' => fake()->phoneNumber(),
            'shipping_country' => fake()->country(),
            'shipping_city' => fake()->city(),
            'shipping_street_address' => fake()->streetAddress(),
        ];
    }

    /**
     * Mark order as delivered.
     */
    public function delivered()
    {
        return $this->state([
            'status' => \App\Enums\OrderStatus::DELIVERED,
        ]);
    }

    /**
     * Attach this order to a specific user.
     */
    public function forUser(User $user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    /**
     * Attach an order item with the given product.
     */
    public function withProduct(Product $product)
    {
        return $this->afterCreating(function (Order $order) use ($product) {

            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_price' => $product->price ?? 100,
                'product_name' => $product->name,
                'quantity' => 1,
                'line_total' => $product->price * 1,
            ]);

            // Optional: update order total
            $order->update([
                'total' => $product->price ?? 100,
            ]);
        });
    }
}
