<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService, protected CartRepository $cartRepo) {}

    public function index()
    {
        return Inertia::render('cart/index', [
            'cart_items' => fn() => CartItemResource::collection($this->cartRepo->getPaginatedCartItems(userId: auth()->id(), relations: ['product.category:id,name'])),
            'order_summary' => [
                'sub_total' => 200,
                'shipping_fee' => 20
            ]
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->validated('product_id'));

        $this->cartService->addItem(auth()->user(), $product, quantity: $request->validated('quantity'));

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
}
