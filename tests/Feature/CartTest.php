<?php

use App\Services\CartService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->userId = createUser();
    $this->productId = createProduct(['stock_qty' => 10]);
});

it('adds a product to the cart', function () {
    loginAs($this->userId);

    $this->post(route('cart.store'), ['product_id' => $this->productId, 'quantity' => 3])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect(cartQuantity($this->userId, $this->productId))->toBe(3);
});

it('raises the quantity instead of adding a second row', function () {
    loginAs($this->userId);

    $this->post(route('cart.store'), ['product_id' => $this->productId, 'quantity' => 3]);
    $this->post(route('cart.store'), ['product_id' => $this->productId, 'quantity' => 2]);

    expect(cartQuantity($this->userId, $this->productId))->toBe(5)
        ->and(DB::table('cart_items')->count())->toBe(1);
});

it('never puts more in the cart than is in stock', function () {
    DB::table('products')->where('product_id', $this->productId)->update(['stock_qty' => 4]);
    loginAs($this->userId);

    $this->post(route('cart.store'), ['product_id' => $this->productId, 'quantity' => 10]);

    expect(cartQuantity($this->userId, $this->productId))->toBe(4);
});

it('refuses products that cannot be bought', function (array $product) {
    $productId = createProduct($product);
    loginAs($this->userId);

    $this->post(route('cart.store'), ['product_id' => $productId, 'quantity' => 1])
        ->assertSessionHasErrors('quantity');

    expect(DB::table('cart_items')->count())->toBe(0);
})->with([
    'removed from the shop' => [['is_active' => false]],
    'out of stock' => [['stock_qty' => 0]],
]);

it('updates and removes items', function () {
    app(CartService::class)->add($this->userId, $this->productId, 3);
    $itemId = DB::table('cart_items')->value('cart_item_id');
    loginAs($this->userId);

    $this->patch(route('cart.update', $itemId), ['quantity' => 2])->assertRedirect(route('cart.index'));
    expect(cartQuantity($this->userId, $this->productId))->toBe(2);

    $this->delete(route('cart.destroy', $itemId))->assertRedirect(route('cart.index'));
    expect(cartQuantity($this->userId, $this->productId))->toBeNull();
});

it('removes an item when its quantity is set to 0', function () {
    app(CartService::class)->add($this->userId, $this->productId, 3);
    $itemId = DB::table('cart_items')->value('cart_item_id');
    loginAs($this->userId);

    $this->patch(route('cart.update', $itemId), ['quantity' => 0]);

    expect(cartQuantity($this->userId, $this->productId))->toBeNull();
});

it('does not let users change other users\' cart items', function () {
    app(CartService::class)->add($this->userId, $this->productId, 3);
    $itemId = DB::table('cart_items')->value('cart_item_id');
    loginAs(createUser());

    $this->patch(route('cart.update', $itemId), ['quantity' => 9]);
    $this->delete(route('cart.destroy', $itemId));

    expect(cartQuantity($this->userId, $this->productId))->toBe(3);
});

it('sends guests to the login page', function () {
    $this->post(route('cart.store'), ['product_id' => $this->productId, 'quantity' => 1])
        ->assertRedirect(route('login'));
    $this->get(route('cart.index'))->assertRedirect(route('login'));
});

it('fixes the cart when products change after being added', function () {
    $parsnip = createProduct(['name' => 'Parsnip Seeds', 'stock_qty' => 10]);
    $melon = createProduct(['name' => 'Melon Seeds']);
    $cart = app(CartService::class);
    $cart->add($this->userId, $parsnip, 5);
    $cart->add($this->userId, $melon, 1);

    DB::table('products')->where('product_id', $parsnip)->update(['stock_qty' => 2]);
    DB::table('products')->where('product_id', $melon)->update(['is_active' => false]);

    loginAs($this->userId);
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('Only 2 of Parsnip Seeds left')
        ->assertSee('Melon Seeds is no longer available');

    expect(cartQuantity($this->userId, $parsnip))->toBe(2)
        ->and(cartQuantity($this->userId, $melon))->toBeNull();
});
