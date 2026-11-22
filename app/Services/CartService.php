<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * All shopping-cart queries, written with the Query Builder.
 *
 * Every method takes the user's ID and only touches that user's cart, so one
 * user can never read or change another user's cart items.
 */
class CartService
{
    /**
     * Get the user's cart ID, creating the cart the first time it's needed.
     */
    public function cartId(int $userId): int
    {
        $cartId = DB::table('carts')->where('user_id', $userId)->value('cart_id');

        if ($cartId !== null) {
            return $cartId;
        }

        return DB::table('carts')->insertGetId([
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'cart_id');
    }

    /**
     * Add a product, or raise its quantity if it's already in the cart.
     * The quantity never goes above the product's stock.
     *
     * Returns the quantity now in the cart, or 0 if the product can't be bought.
     */
    public function add(int $userId, int $productId, int $quantity = 1): int
    {
        $product = DB::table('products')
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first(['stock_qty']);

        if ($product === null || $product->stock_qty < 1 || $quantity < 1) {
            return 0;
        }

        $cartId = $this->cartId($userId);

        $current = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->value('quantity') ?? 0;

        $newQuantity = min($current + $quantity, $product->stock_qty);

        DB::table('cart_items')->updateOrInsert(
            ['cart_id' => $cartId, 'product_id' => $productId],
            ['quantity' => $newQuantity]
        );

        $this->touch($cartId);

        return $newQuantity;
    }

    /**
     * Change an item's quantity, capped at the product's stock.
     * A quantity of 0 or less removes the item.
     *
     * Returns the quantity now in the cart, or 0 if the item was removed or isn't in this user's cart.
     */
    public function update(int $userId, int $cartItemId, int $quantity): int
    {
        $cartId = $this->cartId($userId);

        $item = DB::table('cart_items')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('cart_items.cart_item_id', $cartItemId)
            ->where('cart_items.cart_id', $cartId)
            ->first(['products.stock_qty']);

        if ($item === null) {
            return 0;
        }

        $newQuantity = min($quantity, $item->stock_qty);

        if ($newQuantity < 1) {
            $this->remove($userId, $cartItemId);

            return 0;
        }

        DB::table('cart_items')
            ->where('cart_item_id', $cartItemId)
            ->update(['quantity' => $newQuantity]);

        $this->touch($cartId);

        return $newQuantity;
    }

    /**
     * Remove an item from the user's cart.
     *
     * Returns false if the item isn't in this user's cart.
     */
    public function remove(int $userId, int $cartItemId): bool
    {
        $cartId = $this->cartId($userId);

        $deleted = DB::table('cart_items')
            ->where('cart_item_id', $cartItemId)
            ->where('cart_id', $cartId)
            ->delete();

        if ($deleted > 0) {
            $this->touch($cartId);
        }

        return $deleted > 0;
    }

    /**
     * The user's cart items with product details and a line total for each.
     *
     * @return Collection<int, object>
     */
    public function items(int $userId): Collection
    {
        return DB::table('cart_items')
            ->join('carts', 'carts.cart_id', '=', 'cart_items.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select(
                'cart_items.cart_item_id', 'cart_items.quantity',
                'products.product_id', 'products.sku', 'products.name', 'products.price',
                'products.image_url', 'products.stock_qty', 'products.is_active',
                DB::raw('cart_items.quantity * products.price as line_total')
            )
            ->orderBy('cart_items.added_at')
            ->orderBy('cart_items.cart_item_id')
            ->get();
    }

    /**
     * The cart's subtotal: the sum of quantity × current price, calculated in SQL.
     */
    public function subtotal(int $userId): string
    {
        return (string) DB::table('cart_items')
            ->join('carts', 'carts.cart_id', '=', 'cart_items.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->sum(DB::raw('cart_items.quantity * products.price'));
    }

    /**
     * How many items are in the cart, counting quantities (for the header badge).
     */
    public function itemCount(int $userId): int
    {
        return (int) DB::table('cart_items')
            ->join('carts', 'carts.cart_id', '=', 'cart_items.cart_id')
            ->where('carts.user_id', $userId)
            ->sum('cart_items.quantity');
    }

    /**
     * Record that the cart changed.
     */
    private function touch(int $cartId): void
    {
        DB::table('carts')->where('cart_id', $cartId)->update(['updated_at' => now()]);
    }
}
