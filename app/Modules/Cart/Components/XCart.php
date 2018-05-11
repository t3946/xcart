<?php

namespace Modules\Cart\Components;

use Modules\Distributor\Models\DistributorModel;
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

    /**
     * @param string $property
     * @return CartItem[]
     */
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

    public function onChange(): void {

        parent::onChange();

        OrderHelper::OrderStepsReset($this->getCartNumber());
    }

    public function isValid():bool
    {
        if ($this->getIsEmpty()) {
            return false;
        }

        $groups = $this->getItemsGroupedBy();

        if ($mids = array_keys($groups)) {
            /** @var DistributorModel[] $distrs */
            $distrs = [];
            $dist_valid = [];

            foreach ( DistributorModel::objects()->all(['pk__in' => $mids]) as $model) {
                $distrs[$model->pk] = $model;
            }

            foreach ($groups as $mid => $item) {
                $dist_valid[$mid] = $distrs[$mid]->checkMinimalAmount($item['subtotal']);
            }
        }


        return true;
    }
}
