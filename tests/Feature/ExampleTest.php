<?php

test('the root address redirects to the product catalog', function () {
    $this->get('/')
        ->assertRedirect('/products');

});
