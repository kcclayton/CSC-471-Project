<?php

namespace App\Services;

use App\Exceptions\CheckoutException;
use Illuminate\Support\Facades\DB;

/**
 * Turns a user's cart into an order, using the Query Builder inside one transaction.
 */
class OrderService
{
    public function __construct(private PricingService $pricing) {}

    /**
     * Place an order from the user's cart and return the new order's ID.
     *
     * Everything happens in one transaction: if any step fails, nothing is saved.
     *
     * @param  array{ship_name: string, ship_street: string, ship_city: string, ship_state: string, ship_zip: string, ship_phone: string, ship_email: string}  $shipping
     *
     * @throws CheckoutException when the cart is empty or a product can't be bought
     */
    public function place(int $userId, array $shipping): int
    {
        return DB::transaction(function () use ($userId, $shipping): int {
            // Lock these products until the transaction ends, so two shoppers
            // can't buy the last item at the same moment.
            $items = DB::table('cart_items')
                ->join('carts', 'carts.cart_id', '=', 'cart_items.cart_id')
                ->join('products', 'products.product_id', '=', 'cart_items.product_id')
                ->where('carts.user_id', $userId)
                ->orderBy('products.product_id')
                ->lockForUpdate()
                ->get([
                    'cart_items.cart_id', 'cart_items.quantity',
                    'products.product_id', 'products.sku', 'products.name',
                    'products.price', 'products.stock_qty', 'products.is_active',
                ]);

            if ($items->isEmpty()) {
                throw new CheckoutException('Your cart is empty.');
            }

            foreach ($items as $item) {
                if (! $item->is_active || $item->stock_qty < $item->quantity) {
                    throw new CheckoutException(
                        "Sorry, {$item->name} changed while you were checking out. Please review your cart and try again."
                    );
                }
            }

            $subtotal = $items->sum(fn ($item) => $item->quantity * (int) $item->price);
            $totals = $this->pricing->totals($subtotal);

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'status' => 'pending',
                ...$totals,
                ...$shipping,
            ], 'order_id');

            DB::table('order_items')->insert($items->map(fn ($item) => [
                'order_id' => $orderId,
                'product_id' => $item->product_id,
                'sku_at_purchase' => $item->sku,
                'unit_price' => (int) $item->price,
                'quantity' => $item->quantity,
                'line_total' => $item->quantity * (int) $item->price,
            ])->all());

            foreach ($items as $item) {
                DB::table('products')
                    ->where('product_id', $item->product_id)
                    ->decrement('stock_qty', $item->quantity, ['updated_at' => now()]);
            }

            $cartId = $items->first()->cart_id;
            DB::table('cart_items')->where('cart_id', $cartId)->delete();
            DB::table('carts')->where('cart_id', $cartId)->update(['updated_at' => now()]);

            return $orderId;
        });
    }
}
