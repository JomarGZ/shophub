<?php

use App\Enums\OrderStatus;
use App\Models\Order;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

describe('Order status update', function () {
    beforeEach(function () {
        $this->user = createUser();
        $this->updateOrderStatusRoute = fn (Order $order) => route('orders.status.update', $order);
    });

    dataset('invalid_statuses', [
        [['status' => OrderStatus::OUT_FOR_DELIVERY->value], 'status'],
        [['status' => OrderStatus::PENDING->value], 'status'],
        [['status' => OrderStatus::PROCESSING->value], 'status'],
        [['status' => OrderStatus::REJECTED->value], 'status'],
        [['status' => OrderStatus::PREPARING_FOR_SHIPMENT->value], 'status'],
        [['status' => OrderStatus::FAILED->value], 'status'],
        [['status' => OrderStatus::AWAITING_PAYMENT->value], 'status'],
        [['status' => 9999], 'status'],
        [['status' => 'invalid_status'], 'status'],
        [['status' => ''], 'status'],
        [['status' => null], 'status'],
    ]);

    it('prevents unauthenticated users from updating order status', function () {
        $order = Order::factory()->pending()->forUser(createUser())->withProduct(createProduct())->create();

        $response = patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::CANCELLED->value]);

        $response->assertRedirect(route('login'));
    });

    it('allows updating order status from pending to cancelled', function () {
        $order = Order::factory()->pending()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::CANCELLED->value]);
        $response->assertRedirect(url()->previous());
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::CANCELLED);
    });

    it('allows updating order status from out for delivery to delivered', function () {
        $order = Order::factory()->outForDelivery()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::DELIVERED->value]);
        $response->assertRedirect(url()->previous());
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::DELIVERED);
    });

    it('prevents updating order status from delivered to cancelled', function () {
        $order = Order::factory()->delivered()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::CANCELLED->value]);
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::DELIVERED);
    });

    it('prevents skipping status from pending to delivered', function () {
        $order = Order::factory()->pending()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::DELIVERED->value]);
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::PENDING);
    });

    it('prevents user from getting other users order and update status', function () {
        $otherUser = createUser();
        $order = Order::factory()->pending()->forUser($otherUser)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::CANCELLED->value]);
        $response->assertStatus(404);
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::PENDING);
    });

    it('does not allow changing status once order is cancelled', function () {
        $order = Order::factory()->cancelled()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), ['status' => OrderStatus::DELIVERED->value]);
        $order->refresh();
        expect($order->status)->toBe(OrderStatus::CANCELLED);
    });

    it('fails validation for invalid status inputs', function ($payload, $errorField) {
        $order = Order::factory()->pending()->forUser($this->user)->withProduct(createProduct())->create();

        $response = actingAs($this->user)->patch(($this->updateOrderStatusRoute)($order), $payload);

        $response->assertSessionHasErrors($errorField);
    })->with('invalid_statuses');
});
