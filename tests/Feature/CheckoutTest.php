<?php

use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PricingService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Fix the pricing rules so these tests don't depend on config/shop.php.
    config(['shop.tax_rate' => 0, 'shop.shipping_fee' => 50, 'shop.free_shipping_threshold' => 500]);

    $this->userId = createUser();
    $this->parsnip = createProduct(['sku' => 'SPR-001', 'price' => 20, 'stock_qty' => 100]);
    $this->melon = createProduct(['sku' => 'SUM-001', 'price' => 80, 'stock_qty' => 100]);
});

it('works out order totals', function (int $subtotal, int $shipCost, int $total) {
    expect(app(PricingService::class)->totals($subtotal))
        ->toBe(['subtotal' => $subtotal, 'tax' => 0, 'ship_cost' => $shipCost, 'total' => $total]);
})->with([
    'under the free-shipping amount' => [140, 50, 190],
    'just under it' => [499, 50, 549],
    'exactly at it' => [500, 0, 500],
]);

it('rounds tax to whole gold', function () {
    config(['shop.tax_rate' => 0.08]);

    expect(app(PricingService::class)->totals(145))
        ->toBe(['subtotal' => 145, 'tax' => 12, 'ship_cost' => 50, 'total' => 207]);
});

it('places an order, lowers stock, empties the cart and freezes prices', function () {
    $cart = app(CartService::class);
    $cart->add($this->userId, $this->parsnip, 7);
    $cart->add($this->userId, $this->melon, 2);
    loginAs($this->userId);

    $response = $this->post(route('checkout.store'), shippingDetails());

    $orderId = DB::table('orders')->value('order_id');
    $response->assertRedirect(route('orders.show', $orderId));

    $this->assertDatabaseHas('orders', [
        'order_id' => $orderId, 'user_id' => $this->userId, 'status' => 'pending',
        'subtotal' => 300, 'tax' => 0, 'ship_cost' => 50, 'total' => 350,
        'ship_city' => 'Pelican Town',
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $orderId, 'sku_at_purchase' => 'SPR-001', 'unit_price' => 20, 'quantity' => 7, 'line_total' => 140,
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $orderId, 'sku_at_purchase' => 'SUM-001', 'unit_price' => 80, 'quantity' => 2, 'line_total' => 160,
    ]);

    expect(stockOf($this->parsnip))->toBe(93)
        ->and(stockOf($this->melon))->toBe(98)
        ->and(DB::table('cart_items')->count())->toBe(0);

    DB::table('products')->where('product_id', $this->parsnip)->update(['price' => 25]);
    $this->assertDatabaseHas('order_items', ['sku_at_purchase' => 'SPR-001', 'unit_price' => 20, 'line_total' => 140]);
});

it('gives free shipping at 500g or more', function () {
    app(CartService::class)->add($this->userId, $this->parsnip, 25);
    loginAs($this->userId);

    $this->post(route('checkout.store'), shippingDetails());

    $this->assertDatabaseHas('orders', ['subtotal' => 500, 'ship_cost' => 0, 'total' => 500]);
});

it('refuses the order and changes nothing when stock runs short', function () {
    $cart = app(CartService::class);
    $cart->add($this->userId, $this->parsnip, 7);
    $cart->add($this->userId, $this->melon, 5);
    DB::table('products')->where('product_id', $this->melon)->update(['stock_qty' => 4]);
    loginAs($this->userId);

    $this->post(route('checkout.store'), shippingDetails())
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors('checkout');

    expect(DB::table('orders')->count())->toBe(0)
        ->and(stockOf($this->parsnip))->toBe(100)
        ->and(cartQuantity($this->userId, $this->parsnip))->toBe(7);
});

it('rolls back everything if a step fails partway through', function () {
    $cart = app(CartService::class);
    $cart->add($this->userId, $this->parsnip, 7);

    // Make the database fail right after the order_items insert, i.e. after the
    // order row and its items have already been written.
    DB::listen(function ($query) {
        if (str_starts_with($query->sql, 'insert into "order_items"')) {
            throw new RuntimeException('Simulated database failure');
        }
    });

    expect(fn () => app(OrderService::class)->place($this->userId, shippingDetails()))
        ->toThrow(RuntimeException::class);

    expect(DB::table('orders')->count())->toBe(0)
        ->and(DB::table('order_items')->count())->toBe(0)
        ->and(stockOf($this->parsnip))->toBe(100)
        ->and(cartQuantity($this->userId, $this->parsnip))->toBe(7);
});

it('checks the shipping details', function () {
    app(CartService::class)->add($this->userId, $this->parsnip, 1);
    loginAs($this->userId);

    $this->post(route('checkout.store'), shippingDetails(['ship_phone' => 'call me', 'ship_zip' => '']))
        ->assertSessionHasErrors(['ship_phone', 'ship_zip']);

    expect(DB::table('orders')->count())->toBe(0);
});

it('will not check out an empty cart', function () {
    loginAs($this->userId);

    $this->get(route('checkout.create'))->assertRedirect(route('cart.index'));
    $this->post(route('checkout.store'), shippingDetails())->assertSessionHasErrors('checkout');
});

it('only shows customers their own orders', function () {
    app(CartService::class)->add($this->userId, $this->parsnip, 1);
    $orderId = app(OrderService::class)->place($this->userId, shippingDetails());

    loginAs($this->userId);
    $this->get(route('orders.show', $orderId))->assertOk()->assertSee('Order #'.$orderId);

    loginAs(createUser());
    $this->get(route('orders.show', $orderId))->assertNotFound();
});
