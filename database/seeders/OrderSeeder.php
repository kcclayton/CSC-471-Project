<?php

namespace Database\Seeders;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Each order: customer email, products as SKU => quantity, final status, and how many days ago it was placed.
     *
     * @var list<array{0: string, 1: array<string, int>, 2: string, 3: int}>
     */
    private const ORDERS = [
        ['abigail@epelican.com',   ['SPR-001' => 7, 'SUM-001' => 2], 'delivered', 20],
        ['sebastian@epelican.com', ['FAL-001' => 3],                 'cancelled', 12],
        ['abigail@epelican.com',   ['ALL-001' => 5],                 'shipped',   6],
        ['abigail@epelican.com',   ['FAL-001' => 10, 'SPR-001' => 5], 'pending',  1],
    ];

    /**
     * Items left sitting in carts after the orders are placed: email => [SKU => quantity].
     *
     * @var array<string, array<string, int>>
     */
    private const OPEN_CARTS = [
        'abigail@epelican.com' => ['SUM-001' => 2],
    ];

    public function run(CartService $cart, OrderService $orderService): void
    {
        $users = DB::table('users')->pluck('user_id', 'email');
        $products = DB::table('products')->pluck('product_id', 'sku');

        foreach (self::ORDERS as [$email, $items, $status, $daysAgo]) {
            $userId = $users[$email];

            foreach ($items as $sku => $quantity) {
                $cart->add($userId, $products[$sku], $quantity);
            }

            $orderId = $orderService->place($userId, $this->shippingFor($userId));

            DB::table('orders')
                ->where('order_id', $orderId)
                ->update(['order_date' => now()->subDays($daysAgo)]);

            foreach ($this->stepsTo($status) as $step) {
                $orderService->changeStatus($orderId, $step);
            }
        }

        foreach (self::OPEN_CARTS as $email => $items) {
            foreach ($items as $sku => $quantity) {
                $cart->add($users[$email], $products[$sku], $quantity);
            }
        }
    }

    /**
     * Shipping details built from the user's account, with a made-up address.
     *
     * @return array<string, string>
     */
    private function shippingFor(int $userId): array
    {
        $user = DB::table('users')->where('user_id', $userId)->first();

        return [
            'ship_name' => trim($user->first_name.' '.$user->last_name),
            'ship_street' => $userId.' Willow Lane',
            'ship_city' => 'Pelican Town',
            'ship_state' => 'ST',
            'ship_zip' => '0000'.$userId,
            'ship_phone' => '555-010'.$userId,
            'ship_email' => $user->email,
        ];
    }

    /**
     * The status changes needed to reach a status, following the order rules.
     *
     * @return list<string>
     */
    private function stepsTo(string $status): array
    {
        return match ($status) {
            'shipped' => ['shipped'],
            'delivered' => ['shipped', 'delivered'],
            'cancelled' => ['cancelled'],
            default => [],
        };
    }
}
