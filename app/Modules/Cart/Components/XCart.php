<?php

namespace Modules\Cart\Components;

use Modules\Cart\CartModule;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Helpers\OrderHelper;
use Modules\Sites\Helpers\CurrentSiteHelper;
use Xcart\App\Translate\Translate;

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

        OrderHelper::orderStepsReset($this->getCartNumber());
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

            foreach ( DistributorModel::objects()->all(['pk__in' => $mids]) as $model) {
                $distrs[$model->pk] = $model;
            }

            foreach ($groups as $mid => $item) {
                if (!$distrs[$mid]->checkMinimalAmount($item['subtotal'])) {
                    $min_summa = strip_tags(CurrentSiteHelper::formatCurrency($distrs[$mid]->getMinimalAmount()));
                    $error_message = CartModule::t('The minimum order amount for this product line is') . ' '. $min_summa;
                    \Xcart\App\Main\Xcart::app()->flash->info($error_message);
                    return false;
                }
                foreach ($item['items'] as $product) {
                    if ($p_model = $product->_object->objects()->get(['pk' => $product->_object->pk])) {
                        if ($p_model->forsale === 'N') {
                            return false;
                        }
                    }
                }
            }
        }


        return true;
    }
}
