<?php

namespace Modules\Cart\Components;

use Modules\Cart\Models\CartModel;
use Xcart\App\Main\Xcart;

/**
 * Class SessionStorage
 * @package Modules\Cart\Components
 */
class XTableStorage extends AbstractStorage
{
    public $session_keyName = 'cart_number';

    /** @var CartModel  */
    private $model;
    private $session;

    public function __construct(Cart $cart, $key = 'cart')
    {
        parent::__construct($cart, $key);

        $this->session = Xcart::app()->request->session;

        if (!($cn = $this->session->get($this->session_keyName)))
        {
            $cn = Xcart::app()->getUser()->cart_number;
        }

        if ($cn) {
            $this->model = CartModel::objects()->get(['pk' => $cn]);
        }

        if (!$this->model) {
            $this->model = new CartModel();
        }

        if ($data = $this->model->data) {
            if (!empty($data['discounts'])) {
                $this->cart->setDiscounts($data['discounts']);
            }
            if (!empty($data['cart'])) {
                $this->data = $data['cart'];
            }
        }

        Xcart::app()->event->on('app:end', [$this, 'save']);
    }

    public function getCartNumber()
    {
        return $this->model->id;
    }

    public function add($key, $value)
    {
        $this->saveCartNumber();

        return parent::add($key, $value);
    }

    private function saveCartNumber(): void
    {
        if (!$this->getCartNumber()) {
            $this->model->save();

            $this->session->add($this->session_keyName, $this->getCartNumber());
        }
    }

    public function save(): void
    {
//        $data = [];
//
//        /** @var \Modules\Cart\Components\CartItem $item */
//        foreach ($this->data as $item)
//        {
//            $data[] = [
//                'data' => $item->getData(),
//                'quantity' => $item->getQuantity(),
//                'pk' => $item->getObject()->getUniqueId(),
//                'class' => get_class($item->getObject()),
//            ];
//        }

        $data = [];
        if ($discounts = $this->cart->getDiscounts())
        {
            $data['discounts'] = $discounts;
        }

        if ($this->data) {
            $data['cart'] = $this->data;
        }

        if ($this->model->data != $data) {
            $this->model->data = $data;

            if (!$this->model->data && !$this->model->getIsNewRecord()) {
                $this->model->delete();
            }
            else {
                $this->model->save();
            }
        }
    }
}
