<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout pricing
    |--------------------------------------------------------------------------
    |
    | All amounts are whole numbers of gold, matching products.price.
    | tax_rate is a fraction: 0 means no tax, 0.08 would mean 8%.
    |
    */

    'tax_rate' => 0.05,

    'shipping_fee' => 50,

    'free_shipping_threshold' => 500,

    'low_stock_threshold' => 10,

];
