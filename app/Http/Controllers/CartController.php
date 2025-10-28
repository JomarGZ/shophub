<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService, protected CartRepository $cartRepo) {}

    public function index()
    {
        return Inertia::render('cart/index', [
            'cart_items' => fn () => CartItemResource::collection($this->cartRepo->getPaginatedCartItems(
                userId: auth()->id(),
                relations: ['product.category:id,name'],
                perPage: 1,
                filters: Request::only('search')
            )),
            'filters' => Request::only('search'),
            'order_summary' => [
                'sub_total' => 200,
                'shipping_fee' => 20,
            ],
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->validated('product_id'));

        $this->cartService->addItem(auth()->user(), $product, quantity: $request->validated('quantity'));

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $cartItem->load('product');
        $stock = $cartItem->product->stock;
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$stock],
        ]);
        $this->cartService->updateQuantity(user: auth()->user(), cartItem: $cartItem, quantity: $request->quantity);

        return redirect()->back();
    }

    public function destroy(CartItem $cartItem)
    {
        $this->cartService->removeItem(user: auth()->user(), item: $cartItem);

        return redirect()->back()->with('success', 'Cart item deleted successfully!');
    }
}
