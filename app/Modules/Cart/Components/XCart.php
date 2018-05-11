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
            $distrs = DistributorModel::objects()->all(['pk__in' => $mids]);
            $dist_min = [];
            $dist_valid = [];

            foreach ( $distrs as $model) {
                if ($model->d_minimum_order_amount && $model->d_minimum_order_amount_in_us) {
                    $dist_min[$model->pk] += $model->d_minimum_order_amount_in_us;
                    $dist_valid[$model->pk] = false;
                }
            }

            foreach ($groups as $mid => $item) {
                if ($dist_min[$mid]) {
                    $dist_min[$mid] -= $item->getPrice();
                }

                if ($dist_min[$mid] <= 0 ) {
                    unset($dist_valid[$mid]);
                }
            }

            if ($dist_valid) {
                return false;
            }
        }


        return true;
    }
}
