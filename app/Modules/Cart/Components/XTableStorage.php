<?php

namespace Modules\Cart\Components;

use Xcart\App\Main\Xcart;

/**
 * Class SessionStorage
 * @package Modules\Cart\Components
 */
class XTableStorage extends AbstractStorage
{
//    public $

    private $model;
    private $session;

    public function __construct(Cart $cart, $key = 'cart')
    {
        parent::__construct($cart, $key);

        $cn = null;

        $this->session = Xcart::app()->request->session;
        $this->session->open();
        $ssid = $this->session->getId();


        $this->session = Xcart::app()->getUser()->isNew();

        $this->model = $this->session->get($this->key, []);
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

    public function sync()
    {
        $this->session->add($this->key, $this->getData());
    }
}
