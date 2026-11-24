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
            [ // #1 | Sebastian | Rice
                'email' => 'sebastian@epelican.com',
                'items' => ['ALL-004' => 1],
                'status' => 'delivered',
                'days_ago' => 60,
                'ship_name' => 'Sebastian',
                'ship_street' => '24 Mountain Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0102',
                'ship_email' => 'sebastian@epelican.com',
            ],
            [ // #2 | Penny | Poppy Seeds
                'email' => 'penny@epelican.com',
                'items' => ['SUM-007' => 10],
                'status' => 'delivered',
                'days_ago' => 57,
                'ship_name' => 'Penny',
                'ship_street' => '2 River Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-1027',
                'ship_email' => 'penny@epelican.com',
            ],
            [ // #3 | Leah | Bouquet
                'email' => 'leah@epelican.com',
                'items' => ['ALL-020' => 1],
                'status' => 'delivered',
                'days_ago' => 56,
                'ship_name' => 'Leah',
                'ship_street' => 'Leah\'s Cottage',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0642',
                'ship_email' => 'leah@epelican.com',
            ],
            [ // #4 | Alex | Sugar
                'email' => 'alex@epelican.com',
                'items' => ['ALL-002' => 1],
                'status' => 'delivered',
                'days_ago' => 52,
                'ship_name' => 'Alex',
                'ship_street' => '1 River Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0091',
                'ship_email' => 'alex@epelican.com',
            ],
            [ // #5 | Jodi (1) | Flour
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-003' => 2],
                'status' => 'delivered',
                'days_ago' => 49,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // #6 | Marnie | Fairy Seeds
                'email' => 'marnie@epelican.com',
                'items' => ['ALL-001' => 50],
                'status' => 'delivered',
                'days_ago' => 45,
                'ship_name' => 'Marnie',
                'ship_street' => 'Marnie\'s Ranch',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0075',
                'ship_email' => 'marnie@epelican.com',
            ],
            [ // #7 | Krobus | Blueberry Seeds
                'email' => 'krobus@epelican.com',
                'items' => ['SUM-003' => 1],
                'status' => 'delivered',
                'days_ago' => 40,
                'ship_name' => 'Krobus',
                'ship_street' => 'The Sewers',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-5555',
                'ship_email' => 'krobus@epelican.com',
            ],
            [ // #8 | Robin | Catalogue
                'email' => 'robin@epelican.com',
                'items' => ['ALL-019' => 1],
                'status' => 'delivered',
                'days_ago' => 38,
                'ship_name' => 'Robin',
                'ship_street' => '24 Mountain Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0102',
                'ship_email' => 'robin@epelican.com',
            ],
            [ // #9 | Emily | Apple Sapling
                'email' => 'emily@epelican.com',
                'items' => ['ALL-018' => 1],
                'status' => 'delivered',
                'days_ago' => 36,
                'ship_name' => 'Emily',
                'ship_street' => '2 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0921',
                'ship_email' => 'emily@epelican.com',
            ],
            [ // #10 | Shane | Amoranth
                'email' => 'shane@epelican.com',
                'items' => ['FAL-007' => 10],
                'status' => 'delivered',
                'days_ago' => 33,
                'ship_name' => 'Shane',
                'ship_street' => 'Marnie\'s Ranch',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0075',
                'ship_email' => 'shane@epelican.com',
            ],
            [ // #11 | Maru | Deluxe Speed-Gro
                'email' => 'maru@epelican.com',
                'items' => ['ALL-012' => 15],
                'status' => 'delivered',
                'days_ago' => 31,
                'ship_name' => 'Maru',
                'ship_street' => '24 Mountain Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0102',
                'ship_email' => 'maru@epelican.com',
            ],
            [ // #12 | Jodi (2) | Cauliflower Seeds
                'email' => 'jodi@epelican.com',
                'items' => ['SPR-003' => 1],
                'status' => 'delivered',
                'days_ago' => 29,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // #13 | Gus | Orange Sapling
                'email' => 'gus@epelican.com',
                'items' => ['ALL-015' => 1],
                'status' => 'delivered',
                'days_ago' => 29,
                'ship_name' => 'Gus',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0711',
                'ship_email' => 'gus@epelican.com',
            ],
            [ // #14 | Elliot | Quality Retaining Soil
                'email' => 'elliot@epelican.com',
                'items' => ['ALL-010' => 5],
                'status' => 'delivered',
                'days_ago' => 24,
                'ship_name' => 'Elliot',
                'ship_street' => 'Elliot\'s Cabin',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0342',
                'ship_email' => 'elliot@epelican.com',
            ],
            [ // #15 | Haley | Pepper Seeds
                'email' => 'haley@epelican.com',
                'items' => ['SUM-004' => 3],
                'status' => 'delivered',
                'days_ago' => 22,
                'ship_name' => 'Haley',
                'ship_street' => '2 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0921',
                'ship_email' => 'haley@epelican.com',
            ],
            [ // #16 | Lewis | Oil
                'email' => 'mayorlewis@epelican.com',
                'items' => ['ALL-005' => 10],
                'status' => 'delivered',
                'days_ago' => 19,
                'ship_name' => 'Lewis',
                'ship_street' => 'Mayor\'s Manor',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0001',
                'ship_email' => 'mayorlewis@epelican.com',
            ],
            [ // #17 | Sandy | Jazz Seeds + Fertilizer + Retaining Soil
                'email' => 'sandy@epelican.com',
                'items' => ['SPR-007' => 5, 'ALL-008' => 5, 'ALL-010' => 5],
                'status' => 'delivered',
                'days_ago' => 17,
                'ship_name' => 'Sandy',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0088',
                'ship_email' => 'sandy@epelican.com',
            ],
            [ // #18 | Jodi (3) | Spangle Seeds + Fertilizer
                'email' => 'jodi@epelican.com',
                'items' => ['SUM-008' => 4, 'ALL-007' => 4],
                'status' => 'delivered',
                'days_ago' => 16,
                'ship_name' => 'Jodi',
                'ship_street' => 'Oasis',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // #19 | Rasmodius | Pomegranite Sapling
                'email' => 'thewizard@epelican.com',
                'items' => ['ALL-017' => 30],
                'status' => 'delivered',
                'days_ago' => 14,
                'ship_name' => 'Magnus',
                'ship_street' => 'Wizard\'s Tower',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-7777',
                'ship_email' => 'thewizard@epelican.com',
            ],
            [ // #20 | Demetrius | Peach Sapling
                'email' => 'demetrius@epelican.com',
                'items' => ['ALL-016' => 3],
                'status' => 'delivered',
                'days_ago' => 11,
                'ship_name' => 'Demetrius',
                'ship_street' => '24 Mountain Rd',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0102',
                'ship_email' => 'demetrius@epelican.com',
            ],
            [ // #21 | Jodi (4) | Flour + Sugar + Oil
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-003' => 1, 'ALL-002' => 1, 'ALL-005' => 1],
                'status' => 'delivered',
                'days_ago' => 9,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // #22 | Clint | Pumpkin Seeds
                'email' => 'clint@epelican.com',
                'items' => ['FAL-002' => 3],
                'status' => 'delivered',
                'days_ago' => 5,
                'ship_name' => 'Clint',
                'ship_street' => 'Blacksmith',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0105',
                'ship_email' => 'clint@epelican.com',
            ],
            [ // #23 | Willy | Vinegar 
                'email' => 'willy@epelican.com',
                'items' => ['ALL-006' => 1],
                'status' => 'delivered',
                'days_ago' => 4,
                'ship_name' => 'Willy',
                'ship_street' => 'Fish Shop',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0300',
                'ship_email' => 'willy@epelican.com',
            ],
            [ // #24 | Jodi (4) | Rice 
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-004' => 10],
                'status' => 'cancelled',
                'days_ago' => 4,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
                'ship_email' => 'jodi@epelican.com',
            ],
            [ // #25 | Jodi (5) | Rice 
                'email' => 'jodi@epelican.com',
                'items' => ['ALL-001' => 1],
                'status' => 'pending',
                'days_ago' => 4,
                'ship_name' => 'Jodi',
                'ship_street' => '1 Willow Ln',
                'ship_city' => 'Pelican Town',
                'ship_state' => 'SV',
                'ship_zip' => '00001',
                'ship_phone' => '555-0203',
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
