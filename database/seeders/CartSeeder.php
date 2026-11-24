<?php

namespace Database\Seeders;

use App\Services\CartService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartSeeder extends Seeder
{
    /**
     * What each customer has sitting in their cart: email => [SKU => quantity].
     *
     * @var array<string, array<string, int>>
     */
    private const CARTS = [
        'penny@epelican.com' => ['FAL-004' => 2],
        'leah@epelican.com' => ['SPR-004' => 5],
        'sebastian@epelican.com' => ['SPR-001' => 2],
        'jodi@epelican.com' => ['SPR-003' => 5],
    ];

    public function run(CartService $cart): void
    {
        $users = DB::table('users')->pluck('user_id', 'email');
        $products = DB::table('products')->pluck('product_id', 'sku');

        foreach (self::CARTS as $email => $items) {
            foreach ($items as $sku => $quantity) {
                $added = $cart->add($users[$email], $products[$sku], $quantity);

                if ($added === 0) {
                    throw new RuntimeException("Couldn't add {$sku} to {$email}'s cart. Is it removed or out of stock?");
                }
            }
        }
    }
}
