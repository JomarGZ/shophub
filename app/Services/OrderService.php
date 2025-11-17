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
        protected AddressRepository $addressRepository,
        protected StockService $stockService
    ) {}

    public function execute(User $user, array $data)
    {
        $result = [
            'order' => null,
            'payment_url' => null,
        ];
        $user->loadMissing(['cart.cartItems.product']);
        $this->validateOrderPreconditions($user, $data);

        $cartCalcData = $this->cartCalcService->calculate($user->cart);
        $method = PaymentMethod::from($data['selected_payment_method']);
        $processor = new PaymentProcessor(PaymentMethodFactory::make($method));

        try {
            $order = DB::transaction(function () use ($user, $cartCalcData, $method) {

                $defaultAddress = $this->addressRepository->getAddress($user, default: true);

                $order = $this->createOrder($cartCalcData, $method ?? PaymentMethod::COD, $defaultAddress);
                $this->createOrderItems($order, $cartCalcData['items']);
                $this->stockService->decrementOrderStock($order);

                $user->cart->cartItems()->delete();

                return $order;

            });
            $paymentUrl = $processor->handle($order);
            $result['order'] = $order;
            $result['payment_url'] = $paymentUrl;

            return $result;

        } catch (\Exception $e) {
            Log::error('Order proccessing failed:'.$e->getMessage(), [
                'user_id' => $user->id,
                'stack' => $e->getTraceAsString(),
            ]);

            return $result;
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
        if (empty($data['selected_payment_method'])) {
            throw new \InvalidArgumentException('Payment method is required to process order');
        }

        if (! $user->hasDefaultAddress()) {
            throw new \InvalidArgumentException('Address is required to process order');
        }
    }

    private function createOrder(array $cartTotals, PaymentMethod $paymentMethod, Address $address)
    {
        return $this->orderRepository->create([
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
