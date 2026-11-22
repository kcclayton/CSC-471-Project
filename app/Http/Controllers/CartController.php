<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    /**
     * Show the logged-in user's cart.
     */
    public function index(): View
    {
        $userId = Auth::id();

        return view('cart.index', [
            'items' => $this->cart->items($userId),
            'subtotal' => $this->cart->subtotal($userId),
        ]);
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
