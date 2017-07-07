<?php

namespace Modules\Cart\Interfaces;

use Modules\Cart\Components\Cart;
use Modules\Cart\Components\CartItem;

/**
 * Interface IDiscount
 * @package Modules\Cart\Components
 */
interface IDiscount
{
    /**
     * Apply discount to CartItem position. If new prices is equal old price - return old price.
     * @param Cart $cart
     * @param CartItem $item
     * @return int|float new price with discount
     */
    public function applyDiscount(Cart $cart, CartItem $item);
}
