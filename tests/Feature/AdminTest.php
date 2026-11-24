<?php

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->adminId = createUser(['is_admin' => true]);
    $this->customerId = createUser();
});

dataset('admin pages', [
    'admin.dashboard',
    'admin.seasons.index',
    'admin.products.index',
    'admin.orders.index',
    'admin.users.index',
]);

it('sends guests to the login page', function (string $page) {
    $this->get(route($page))->assertRedirect(route('login'));
})->with('admin pages');

it('blocks customers', function (string $page) {
    loginAs($this->customerId);

    $this->get(route($page))->assertForbidden();
})->with('admin pages');

it('lets admins in', function (string $page) {
    loginAs($this->adminId);

    $this->get(route($page))->assertOk();
})->with('admin pages');

it('shows user profile pictures in the admin list and overview', function () {
    $avatarPath = 'avatars/customer.png';
    DB::table('users')->where('user_id', $this->customerId)->update(['avatar_url' => $avatarPath]);
    loginAs($this->adminId);

    $avatarUrl = asset('storage/'.$avatarPath);

    $this->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($avatarUrl);
    $this->get(route('admin.users.show', $this->customerId))
        ->assertOk()
        ->assertSee($avatarUrl);
});

it('blocks customers from admin actions', function () {
    $productId = createProduct();
    loginAs($this->customerId);

    $this->patch(route('admin.products.remove', $productId))->assertForbidden();

    $this->assertDatabaseHas('products', ['product_id' => $productId, 'is_active' => true]);
});

it('lets admins create a product with a capitalised SKU', function () {
    $season_ids = [$seasonId];
    loginAs($this->adminId);

    $this->post(route('admin.products.store'), [
        'sku' => 'fal-002', 'name' => 'Pumpkin Seeds', 'price' => 100,
        'season_id' => $seasonId, 'stock_qty' => 20,
    ])->assertRedirect()->assertSessionHasNoErrors();

    $this->assertDatabaseHas('products', ['sku' => 'FAL-002', 'name' => 'Pumpkin Seeds', 'is_active' => true]);
});

it('rejects a duplicate SKU', function () {
    createProduct(['sku' => 'SPR-001']);
    loginAs($this->adminId);

    $this->post(route('admin.products.store'), [
        'sku' => 'spr-001', 'name' => 'Copy', 'price' => 10,
        'season_ids' => [createSeason('Summer')], 'stock_qty' => 1,
    ])->assertSessionHasErrors('sku');
});

it('removes products from the shop and restores them', function () {
    $productId = createProduct();
    loginAs($this->adminId);

    $this->patch(route('admin.products.remove', $productId))->assertRedirect();
    $this->assertDatabaseHas('products', ['product_id' => $productId, 'is_active' => false]);
    $this->get(route('products.show', $productId))->assertNotFound();

    $this->patch(route('admin.products.restore', $productId))->assertRedirect();
    $this->assertDatabaseHas('products', ['product_id' => $productId, 'is_active' => true]);
});

it('puts stock back when an order is cancelled, but only once', function () {
    $productId = createProduct(['stock_qty' => 10]);
    app(CartService::class)->add($this->customerId, $productId, 3);
    $orderId = app(OrderService::class)->place($this->customerId, shippingDetails());
    expect(stockOf($productId))->toBe(7);

    loginAs($this->adminId);

    $this->patch(route('admin.orders.status', $orderId), ['status' => 'cancelled'])->assertSessionHasNoErrors();
    expect(stockOf($productId))->toBe(10);

    $this->patch(route('admin.orders.status', $orderId), ['status' => 'cancelled'])->assertSessionHasErrors('status');
    expect(stockOf($productId))->toBe(10);
});

it('only allows status changes that follow the rules', function () {
    $productId = createProduct();
    app(CartService::class)->add($this->customerId, $productId, 1);
    $orderId = app(OrderService::class)->place($this->customerId, shippingDetails());
    loginAs($this->adminId);

    $this->patch(route('admin.orders.status', $orderId), ['status' => 'delivered'])->assertSessionHasErrors('status');
    $this->patch(route('admin.orders.status', $orderId), ['status' => 'shipped'])->assertSessionHasNoErrors();
    $this->patch(route('admin.orders.status', $orderId), ['status' => 'cancelled'])->assertSessionHasErrors('status');

    $this->assertDatabaseHas('orders', ['order_id' => $orderId, 'status' => 'shipped']);
});

it('will not delete a season that still has products', function () {
    $seasonId = createSeason('Winter');
    createProduct(['season_id' => $seasonId]);
    loginAs($this->adminId);

    $this->delete(route('admin.seasons.destroy', $seasonId))->assertSessionHasErrors('season');

    $this->assertDatabaseHas('seasons', ['season_id' => $seasonId]);
});

it('saves several seasons for one product and requires at least one', function () {
    $summer = createSeason('Summer');
    $fall = createSeason('Fall');
    loginAs($this->adminId);

    $this->post(route('admin.products.store'), [
        'sku' => 'SUM-002', 'name' => 'Corn Seeds', 'price' => 150, 'stock_qty' => 10,
        'season_ids' => [$summer, $fall],
    ])->assertSessionHasNoErrors();

    $productId = DB::table('products')->where('sku', 'SUM-002')->value('product_id');

    expect(DB::table('product_seasons')->where('product_id', $productId)->orderBy('season_id')->pluck('season_id')->all())
        ->toBe([$summer, $fall]);

    $this->put(route('admin.products.update', $productId), [
        'sku' => 'SUM-002', 'name' => 'Corn Seeds', 'price' => 150, 'stock_qty' => 10,
    ])->assertSessionHasErrors('season_ids');

    expect(DB::table('product_seasons')->where('product_id', $productId)->count())->toBe(2);
});
