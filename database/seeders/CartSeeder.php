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
        'abigail@epelican.com' => ['SUM-001' => 2, 'SUM-002' => 1],
        'sebastian@epelican.com' => ['SPR-001' => 10],
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
