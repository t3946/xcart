<?php

namespace Modules\Cart\Components;

use Modules\Cart\Interfaces\ICartStorage;

/**
 * Class SessionStorage
 * @package Modules\Cart\Components
 */
abstract class AbstractStorage implements ICartStorage
{
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var string
     */
    protected $key;
    protected $data = [];

    public function __construct(Cart $cart, $key = 'cart')
    {
        $this->key = $key;
        $this->cart = $cart;
        $this->data = [];
    }

    /**
     * @param $key
     * @return \Modules\Cart\Components\CartItem
     */
    public function get($key):? CartItem
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key): bool
    {
        if ($this->has($key)) {
            $this->cart->getEventManager()->trigger('cart:change');
            $this->cart->getEventManager()->trigger('cart:removeItem', [$this->data[$key]], $this->cart);
            unset($this->data[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @param $value
     * @return AbstractStorage
     */
    public function add($key, $value): AbstractStorage
    {
        $this->cart->getEventManager()->trigger('cart:change');
        $this->cart->getEventManager()->trigger('cart:addItem', [$value], $this->cart);
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->data);
    }

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->data = [];
        return $this;
    }

    /**
     * @return \Modules\Cart\Components\CartItem[]
     */
    public function getItems(): array
    {
        $items = [];
        foreach ($this->getData() as $item) {
            $items[] = $item;
        }
        return $items;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->getData());
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data ?: [];
    }
}
