<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when an order can't be placed, e.g. the cart is empty or stock ran out.
 * The message is safe to show to the shopper.
 */
class CheckoutException extends RuntimeException
{
    //
}
