<?php

namespace Modules\Cart\Components;

use Xcart\App\Main\Xcart;

/**
 * Class SessionStorage
 * @package Modules\Cart\Components
 */
class XSessionStorage
{
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var string
     */
    protected $key;
    protected $data;
    protected $session;

    public function __construct(Cart $cart, $key = 'cart')
    {
        $this->key = $key;
        $this->cart = $cart;
        $this->session = Xcart::app()->request->session;

        $this->data = $this->session->get($this->key, []);
        if (!is_array($this->data)) {
            $this->data = [];
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return unserialize($this->data[$key]);
        }
        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            $this->cart->getEventManager()->trigger('cart:removeItem', [unserialize($this->data[$key])], $this->cart);
            unset($this->data[$key]);
            $this->sync();
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function add($key, $value)
    {
        $this->cart->getEventManager()->trigger('cart:addItem', [$value], $this->cart);
        $this->data[$key] = serialize($value);
        $this->sync();
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->data = [];
        $this->sync();
        return $this;
    }

    /**
     * @return \Modules\Cart\Components\CartItem[]
     */
    public function getItems()
    {
        $items = [];
        foreach ($this->getData() as $item) {
            $items[] = unserialize($item);
        }
        return $items;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->getData());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data ?: [];
    }

    public function sync()
    {
        $this->session->add($this->key, $this->getData());
    }
}
