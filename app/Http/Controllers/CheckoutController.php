<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Exceptions\CheckoutException;
use App\Services\OrderService;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private PricingService $pricing,
        private OrderService $orders,
    ) {}

    /**
     * Show the shipping form and order summary.
     */
    public function create(): View|RedirectResponse
    {
        $userId = Auth::id();
        $notices = $this->cart->reconcile($userId);
        $items = $this->cart->items($userId);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $user = Auth::user();

        $lastOrder = DB::table('orders')
            ->where('user_id', $userId)
            ->orderByDesc('order_date')
            ->first(['ship_street', 'ship_city', 'ship_state', 'ship_zip', 'ship_phone']);

        $defaults = [
            'ship_name'   => trim($user->first_name.' '.$user->last_name),
            'ship_street' => $lastOrder->ship_street ?? '',
            'ship_city'   => $lastOrder->ship_city ?? '',
            'ship_state'  => $lastOrder->ship_state ?? '',
            'ship_zip'    => $lastOrder->ship_zip ?? '',
            'ship_phone'  => $lastOrder->ship_phone ?? '',
            'ship_email'  => $user->email,
        ];

        return view('checkout.create', [
            'notices' => $notices,
            'items' => $items,
            'totals' => $this->pricing->totals($this->cart->subtotal($userId)),
            'defaults' => $defaults,
        ]);
    }

    /**
     * Place the order. If something in the cart changed, send the shopper back to review it.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $orderId = $this->orders->place(Auth::id(), $request->validated());
        } catch (CheckoutException $e) {
            return redirect()->route('cart.index')->withErrors(['checkout' => $e->getMessage()]);
        }

        return redirect()->route('products.index')
            ->with('status', "Thank you! Your order #{$orderId} has been placed.");
    }
}
