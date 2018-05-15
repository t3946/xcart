<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 15.05.2018
 * Time: 11:08
 */

namespace Modules\Cart\Helpers;


use Aws\Common\Enum;

class Stages extends Enum
{
    const SHOPPING_CART = 0;
    const SHIPPING_ADDRESS = 1;
    const SHIPPING_PAYMENT_OPTIONS = 2;
    const ORDER_REVIEW = 3;
    const PAYMENT = 4;
}