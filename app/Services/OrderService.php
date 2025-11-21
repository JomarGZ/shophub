<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Factories\PaymentMethodFactory;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\OrderRepository;
use App\Services\Cart\CartCalculationService;
use App\Services\Payments\PaymentProcessor;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected CartCalculationService $cartCalcService,
        protected CartService $cartService,
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
            if ($method->isOnline()) {
                $existingOrder = $user->orders()
                    ->where('payment_method', $method)
                    ->where('payment_status', PaymentStatus::UNPAID)
                    ->where('status', OrderStatus::PENDING)
                    ->latest()
                    ->first();
                    
                if ($existingOrder) {
                    $existingOrder->status = OrderStatus::CANCELLED;
                    $existingOrder->save();
                }
            }
            $order = DB::transaction(function () use ($user, $cartCalcData, $method) {

                $defaultAddress = $this->addressRepository->getAddress($user, default: true);

                $order = $this->createOrder($cartCalcData, $method ?? PaymentMethod::COD, $defaultAddress);
                $this->createOrderItems($order, $cartCalcData['items']);

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
            'payment_status' => PaymentStatus::UNPAID,
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

   
    public function completeOrder(Order $order, array $data = [], ?OrderStatus $status, $method = null)
    {
        try {
            DB::transaction(function () use ($order, $data, $status, $method) {
                $order->update([
                    'payment_status' => PaymentStatus::tryFrom($data['payment_status'] ?? null),
                    'status' => $status ?? OrderStatus::PENDING,
                    'transaction_id' => $data['payment_intent'] ?? null,
                    'external_reference' => $data['id'] ?? null,
                    'paid_at' => PaymentStatus::tryFrom($data['payment_status']) === PaymentStatus::PAID ? now() : null
                ]);
                $this->stockService->decrementOrderStock($order);
                $this->cartService->removePurchaseItem($order->user_id, $order->orderItems->pluck('product_id')->toArray());

                Log::info('Order Completed', [
                    'order_id' => $order->id,
                    'amount' => $order->total,
                    'provider' => $method
                ]);
            });
        } catch (Exception $e) {
            Log::error('Failed to complete order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

    }
}
