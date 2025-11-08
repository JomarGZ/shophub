<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Factories\PaymentMethodFactory;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\OrderRepository;
use App\Services\Cart\CartCalculationService;
use App\Services\Payments\PaymentProcessor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected CartCalculationService $cartCalcService,
        protected OrderRepository $orderRepository,
        protected AddressRepository $addressRepository
    ) {}

    public function execute(User $user, array $data)
    {
        $user->loadMissing(['cart.cartItems.product']);
        $this->validateOrderPreconditions($user, $data);

        $cartCalcData = $this->cartCalcService->calculate($user->cart);

        $processor = new PaymentProcessor(PaymentMethodFactory::make($data['payment_method']));

        try {
            return DB::transaction(function () use ($data, $user, $cartCalcData, $processor) {

                $defaultAddress = $this->addressRepository->getAddress($user, default: true);

                $order = $this->createOrder($user, $cartCalcData, $data['payment_method'], $defaultAddress);

                $this->createOrderItems($order, $cartCalcData['items']);

                $processor->handle($order);

                $user->cart->cartItems()->delete();

                return $order;

            });

        } catch (\Exception $e) {
            Log::error('Order proccessing failed:'.$e->getMessage(), [
                'user_id' => $user->id,
                'stack' => $e->getTraceAsString(),
            ]);
        }
    }

    private function validateOrderPreconditions(User $user, array $data): void
    {
        if (! $user->cart) {
            throw new \Exception('User does not have a cart');
        }
        if ($user->cart->cartItems->isEmpty()) {
            throw new \Exception('Cart is empty');
        }
        if (empty($data['payment_method'])) {
            throw new \InvalidArgumentException('Payment method is required to process order');
        }

        if (! $user->hasDefaultAddress()) {
            throw new \InvalidArgumentException('Address is required to process order');
        }
    }

    private function createOrder(User $user, array $cartTotals, PaymentMethod $paymentMethod, Address $address)
    {
        return $this->orderRepository->create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'status' => OrderStatus::PENDING,
            'subtotal' => $cartTotals['subtotal'],
            'shipping_fee' => $cartTotals['shipping_fee'],
            'total' => $cartTotals['total'],
            'payment_method' => $paymentMethod,
            'shipping_full_name' => $address->full_name,
            'shipping_phone' => $address->phone,
            'shipping_country' => $address->country->name,
            'shipping_city' => $address->city->name,
            'shipping_street_address' => $address->street_address,
        ]);
    }

    private function createOrderItems(Order $order, Collection $items)
    {
        $items->each(fn ($item) => $order->orderItems()->create($item));
    }
}
