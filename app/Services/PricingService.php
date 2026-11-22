<?php

namespace App\Services;

/**
 * Checkout pricing rules: tax and shipping, in whole numbers of gold.
 *
 * The rates and amounts live in config/shop.php so they can change without code changes.
 */
class PricingService
{
    /**
     * Work out an order's totals from the cart subtotal.
     *
     * @return array{subtotal: int, tax: int, ship_cost: int, total: int}
     */
    public function totals(int|string $subtotal): array
    {
        $subtotal = (int) $subtotal;

        $tax = (int) round($subtotal * config('shop.tax_rate'));

        $shipCost = $subtotal >= config('shop.free_shipping_threshold')
            ? 0
            : (int) config('shop.shipping_fee');

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'ship_cost' => $shipCost,
            'total' => $subtotal + $tax + $shipCost,
        ];
    }
}