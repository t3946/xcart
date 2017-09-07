<?php

namespace Modules\Product\Controllers;

use Modules\Cart\Controllers\BaseCartController;
use Modules\Product\Models\ProductModel;
use Xcart\App\Main\Xcart;

class CartController extends BaseCartController
{
    public $defaultListRoute = 'catalog:cart:list';
    public $listRoute = 'catalog:cart:list';

    public function actionAdd($uniqueId, $quantity = 1)
    {
        $quantity = $this->getRequest()->post->get('quantity', 1);

        parent::actionAdd($uniqueId, $quantity);
    }

    public function actionProductsAdd()
    {
        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $this->addInternal($item['id'], $item['quantity']);
            }
        }

        $this->actionGetQuantity();
    }

    protected function addInternal($uniqueId, $quantity = 1)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if (!$model->isOutOfStock) {
            Xcart::app()->cart->add($model, $quantity, null, $this->getRequest()->post->get('data', []));

            return true;
        }

        return false;
    }
}