<?php

namespace Modules\Cart\Components;

use Modules\Cart\Interfaces\ICartStorage;
use Xcart\App\Main\Xcart;

/**
 * Class SessionStorage
 * @package Modules\Cart\Components
 */
class XSessionStorage extends AbstractStorage
{
    /**
     * @var string
     */
    protected $session;

    public function __construct(Cart $cart, $key = 'cart')
    {
        parent::__construct($cart, $key);

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
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        if (parent::remove($key))
        {
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
        parent::add($key, $value);
        $this->sync();
        return $this;
    }
    /**
     * @return $this
     */
    public function clear()
    {
        parent::clear();
        $this->sync();
        return $this;
    }

    public function sync()
    {
        $this->session->add($this->key, $this->getData());
    }
}
