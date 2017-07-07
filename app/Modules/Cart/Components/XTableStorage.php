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
            $this->model->save();
            $this->session->add($this->session_keyName, $this->getCartNumber());
        }

        $this->data = $this->model->data ?: [];
    }

    public function getCartNumber()
    {
        return $this->model->id;
    }

    public function save()
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

        $this->model->data = $this->data;
        $this->model->save(['data']);
    }
}
