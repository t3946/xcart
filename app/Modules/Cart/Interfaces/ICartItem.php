<?php
namespace Modules\Cart\Interfaces;

interface ICartItem
{
    /**
     * @return mixed unique product identification
     */
    public function getUniqueId();

    /**
     * @return int|float
     */
    public function getPrice();

    /**
     * @param $quantity int
     * @param $type mixed
     * @param $data array|null
     * @return int|float return total product price based on quantity and weight type
     */
    public function recalculate($quantity, $type, $data);
}
