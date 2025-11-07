<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\Cart\CartCalculationService;
use App\Services\CartService;
use Illuminate\Http\Request as ValidationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CartRepository $cartRepo,
        protected CartCalculationService $cartCalculationService
    ) {}

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(request()->user());
        $this->cartService->syncQuantitiesWithStock($cart);
        $orderSummary = $this->cartCalculationService->calculate($cart);

        return Inertia::render('cart/index', [
            'cart_items' => fn () => CartItemResource::collection($this->cartRepo->getPaginatedItems(
                userId: auth()->id(),
                relations: ['product.category:id,name'],
                perPage: 10,
                filters: Request::only('search')
            )),
            'filters' => Request::only('search'),
            'order_summary' => $orderSummary,
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->validated('product_id'));

        $this->cartService->addItem(auth()->user(), $product, quantity: $request->validated('quantity'));

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(ValidationRequest $request, CartItem $cartItem)
    {
        Gate::authorize('update', $cartItem);
        $cartItem->load('product');
        $stock = $cartItem->product->stock;
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$stock],
        ]);
        $this->cartService->updateQuantity(cartItem: $cartItem, quantity: $request->quantity);

        return redirect()->back();
    }

    public function destroy(CartItem $cartItem)
    {
        Gate::authorize('delete', $cartItem);

        $this->cartService->removeItem(item: $cartItem);

        return redirect()->back()->with('success', 'Cart item deleted successfully!');
    }
}
