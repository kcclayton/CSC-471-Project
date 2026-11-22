<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    /**
     * Show the logged-in user's cart, after fixing any items that changed since they were added.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $notices = $this->cart->reconcile($userId);

        return view('cart.index', [
            'notices' => $notices,
            'items' => $this->cart->items($userId),
            'subtotal' => $this->cart->subtotal($userId),
        ]);
    }

    /**
     * Add a product to the cart from its product page.
     */
    public function store(AddToCartRequest $request): RedirectResponse
    {
        $quantity = $this->cart->add(
            Auth::id(),
            $request->integer('product_id'),
            $request->integer('quantity')
        );

        if ($quantity === 0) {
            return back()->withErrors(['quantity' => 'Sorry, this product can\'t be added to your cart right now.']);
        }

        return back()->with('status', "Added to your cart. You now have {$quantity} of this item.");
    }

    /**
     * Change one item's quantity. A quantity of 0 removes it.
     */
    public function update(UpdateCartItemRequest $request, int $item): RedirectResponse
    {
        $requested = $request->integer('quantity');
        $quantity = $this->cart->update(Auth::id(), $item, $requested);

        $message = match (true) {
            $quantity === 0 => 'Item removed from your cart.',
            $quantity < $requested => "Only {$quantity} in stock, so your quantity was set to {$quantity}.",
            default => 'Cart updated.',
        };

        return redirect()->route('cart.index')->with('status', $message);
    }

    /**
     * Remove one item from the cart.
     */
    public function destroy(int $item): RedirectResponse
    {
        $this->cart->remove(Auth::id(), $item);

        return redirect()->route('cart.index')->with('status', 'Item removed from your cart.');
    }
}
