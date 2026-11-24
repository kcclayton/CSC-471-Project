<?php

it('confirms successful registration on the product page', function () {
    $response = $this->post(route('register'), [
        'first_name' => 'Avery',
        'last_name' => 'Farmer',
        'email' => 'avery@example.com',
        'password' => 'P@ssword123',
        'password_confirmation' => 'P@ssword123',
    ]);

    $response->assertRedirect('/products')
        ->assertSessionHas('status', 'Your account was created successfully.');
    $this->assertDatabaseHas('users', ['email' => 'avery@example.com']);
    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('Your account was created successfully.');
});

it('confirms logout on the product page', function () {
    loginAs(createUser());

    $this->post(route('logout'))
        ->assertRedirect('/products')
        ->assertSessionHas('status', 'You have been logged out successfully.');
    $this->get(route('products.index'))
        ->assertOk()
        ->assertSee('You have been logged out successfully.');
});
