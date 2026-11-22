<?php

it('lists a product with two seasons under both of them', function () {
    $spring = createSeason('Spring');
    $summer = createSeason('Summer');
    $fall = createSeason('Fall');
    createProduct(['name' => 'Corn Seeds', 'season_ids' => [$summer, $fall]]);
    createProduct(['name' => 'Parsnip Seeds', 'season_ids' => [$spring]]);

    $this->get(route('products.index', ['season' => $summer]))
        ->assertSee('Corn Seeds')
        ->assertSee('Summer · Fall')
        ->assertDontSee('Parsnip Seeds');

    $this->get(route('products.index', ['season' => $fall]))
        ->assertSee('Corn Seeds');

    $this->get(route('products.index', ['season' => $spring]))
        ->assertSee('Parsnip Seeds')
        ->assertDontSee('Corn Seeds');
});
