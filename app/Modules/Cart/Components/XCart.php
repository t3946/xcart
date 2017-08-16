<?php

namespace Modules\Cart\Components;

class XCart extends Cart
{
    public $storageConfig = [
//        'class' => '\Modules\Cart\Components\XSessionStorage,
        'class' => '\Modules\Cart\Components\XTableStorage',
    ];

    public function init()
    {
        parent::init();

        $this->getEventManager()->on('app:end', [$this, 'save']);
    }

    public function getCartNumber()
    {
        $this->getStorage()->getCartNumber();
    }

    public function save()
    {
        $this->getStorage()->save($this->discounts);
    }

    public function getItemsGroupedBy($property = 'manufacturerid')
    {
        $groups = [];

        /** @var \Modules\Cart\Components\CartItem $cartItem */
        foreach ($this->getItems() as $key => $cartItem)
        {
            $gi = $cartItem->getObject()->{$property};

            if (!isset($groups[$gi])) {
                $groups[$gi] = [
                    'items' => [],
                    'subtotal' => 0,
                ];
            }

            $groups[$gi]['items'][$key] = $cartItem;
            $groups[$gi]['subtotal'] += $cartItem->getPrice();
        }

        return $groups;
    }
}
