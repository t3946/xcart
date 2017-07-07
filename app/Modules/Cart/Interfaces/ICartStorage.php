<?php
namespace Modules\Cart\Interfaces;

use Modules\Cart\Components\Cart;

interface ICartStorage
{
    public function __construct(Cart $cart, $cart_id);
    public function get($key);
    public function has($key);
    public function remove($key);
    public function add($key, $value);
    public function clear();
    public function count();
    public function getItems();

}