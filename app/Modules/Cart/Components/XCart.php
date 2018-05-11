<?php

namespace Modules\Cart\Components;

use Modules\Order\Helpers\OrderHelper;

class XCart extends Cart
{
    public $storageConfig = [
//        'class' => '\Modules\Cart\Components\XSessionStorage,
        'class' => \Modules\Cart\Components\XTableStorage::class,
    ];


    public function getCartNumber() :? int
    {
        return $this->getStorage()->getCartNumber();
    }

    public function getItemsGroupedBy($property = 'manufacturerid'): array
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
                    'discount' => 0,
                ];
            }

            $groups[$gi]['items'][$key] = $cartItem;
            $groups[$gi]['subtotal'] += $cartItem->getPrice();
            $groups[$gi]['discount'] += $cartItem->getDiscountSum();
        }

        return $groups;
    }

    public function onChange($item): void {

        parent::onChange($item);

        OrderHelper::OrderStepsReset($this->getCartNumber());
    }
}
