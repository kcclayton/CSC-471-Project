<?php

namespace Database\Seeders;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{

    public function run(CartService $cart, OrderService $orderService): void 
    {
        $users = DB::table('users')->pluck('user_id', 'email');
        $products = DB::table('products')->pluck('product_id', 'sku');

        $orders = [
            // SEBASTIAN
            [ // Shipping Order
                'email' => 'sebastian@epelican.com',
                'items' => ['ALL-004' => 30],
                'status' => 'pending',
                'days_ago' => 20,
                'ship_name' => 'Sebastian',
                'ship_street' => '24 Mountain Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0102',
                'ship_email' => 'sebastian@epelican.com',
            ],
            // ALEX
            [ // Cancelled Order
                'email' => 'alex@epelican.com',
                'items' => ['FAL-002' => 20],
                'status' => 'cancelled',
                'days_ago' => 10,
                'ship_name' => 'Alex',
                'ship_street' => '1 River Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0001',
                'ship_email' => 'alex@epelican.com',
            ],
            [ // Delivered Order
                'email' => 'alex@epelican.com',
                'items' => ['FAL-002' => 2],
                'status' => 'delivered',
                'days_ago' => 10,
                'ship_name' => 'Alex',
                'ship_street' => '1 River Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0001',
                'ship_email' => 'alex@epelican.com',
            ],
            
            // JODI
            [ // Delivered Order
                'email' => 'jodi@epelican.com',
                'items' => ['SPR-003' => 1],
                'status' => 'delivered',
                'days_ago' => 12,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0105',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // Delivered Order
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-002' => 2, 'ALL-003' => 2, 'ALL-005' => 1],
                'status' => 'delivered',
                'days_ago' => 8,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0105',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // Cancelled Order
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-001' => 50],
                'status' => 'cancelled',
                'days_ago' => 2,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0105',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // Shipping Order
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-001' => 5],
                'status' => 'pending',
                'days_ago' => 2,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0105',
                'ship_email' => 'jodi@epelican.com',
            ],
            
        ];

        foreach ($orders as $order) {
            $userId = $users[$order['email']];

            foreach ($order['items'] as $sku => $quantity) {
                $cart->add($userId, $products[$sku], $quantity);
            }

            $orderId = $orderService->place($userId, Arr::only($order, [
                'ship_name', 'ship_street', 'ship_city', 'ship_state',
                'ship_zip', 'ship_phone', 'ship_email',
            ]));

            DB::table('orders')
                ->where('order_id', $orderId)
                ->update(['order_date' => now()->subDays($order['days_ago'])]);

            foreach ($this->stepsTo($order['status']) as $step) {
                $orderService->changeStatus($orderId, $step);
            }
        }
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
