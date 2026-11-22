<?php

use App\Auth\ShopUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Feature tests run against the shop_testing database (see phpunit.xml).
| RefreshDatabase runs the migrations once, then undoes each test's changes,
| so every test starts with empty tables.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
|
| Test data is created with Query Builder inserts, the same way the app works.
| Each helper returns the new row's ID. Pass an array to change any column.
|
*/

function createUser(array $overrides = []): int
{
    return DB::table('users')->insertGetId([
        'email' => fake()->unique()->safeEmail(),
        'pwrd_hash' => Hash::make('password'),
        'first_name' => 'Test',
        'last_name' => 'User',
        'is_admin' => false,
        ...$overrides,
    ], 'user_id');
}

function createSeason(string $name = 'Spring'): int
{
    return DB::table('seasons')->insertGetId(['name' => $name], 'season_id');
}

function createProduct(array $overrides = []): int
{
    $seasonId = $overrides['season_id']
        ?? DB::table('seasons')->value('season_id')
        ?? createSeason();

    return DB::table('products')->insertGetId([
        'sku' => 'TST-'.fake()->unique()->numerify('####'),
        'name' => 'Test Seeds',
        'description' => null,
        'price' => 20,
        'image_url' => null,
        'season_id' => $seasonId,
        'stock_qty' => 10,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
        ...$overrides,
    ], 'product_id');
}

/**
 * Log in as a user for the rest of the test.
 */
function loginAs(int $userId): void
{
    test()->actingAs(new ShopUser(DB::table('users')->where('user_id', $userId)->first()));
}

function stockOf(int $productId): int
{
    return DB::table('products')->where('product_id', $productId)->value('stock_qty');
}

function cartQuantity(int $userId, int $productId): ?int
{
    return DB::table('cart_items')
        ->join('carts', 'carts.cart_id', '=', 'cart_items.cart_id')
        ->where('carts.user_id', $userId)
        ->where('cart_items.product_id', $productId)
        ->value('cart_items.quantity');
}

/**
 * Valid checkout form data. Pass an array to change any field.
 *
 * @return array<string, string>
 */
function shippingDetails(array $overrides = []): array
{
    return [
        'ship_name' => 'Test User',
        'ship_street' => '1 Willow Lane',
        'ship_city' => 'Pelican Town',
        'ship_state' => 'ST',
        'ship_zip' => '00001',
        'ship_phone' => '555-0101',
        'ship_email' => 'test@example.com',
        ...$overrides,
    ];
}
