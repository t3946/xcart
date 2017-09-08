<?php

namespace Modules\Product\Controllers;

use Modules\Cart\CartModule;
use Modules\Cart\Controllers\BaseCartController;
use Modules\Product\Models\ProductModel;
use Xcart\App\Main\Xcart;

class CartController extends BaseCartController
{
    public $defaultListRoute = 'cart:list';
    public $listRoute = 'cart:list';

    public function actionAdd($uniqueId, $quantity = 1)
    {
        $quantity = $this->getRequest()->post->get('quantity', 1);

        parent::actionAdd($uniqueId, $quantity);
    }

    public function actionProductsAdd()
    {
        $oldQuantity = $this->getCart()->getQuantity();

        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $this->addInternal($item['id'], $item['quantity']);
            }
        }

        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();

        if ($isAjax) {
            $this->jsonResponse([
                'status' => true,
                'total' => $cart->getTotal(),
                'quantity' => $cart->getQuantity(),
                'old_quantity' => $oldQuantity,
                'items' => $this->getCartProductsArray(),
                'message' => [
                    'title' => CartModule::t('Product(s) added')
                ],
            ]);
            Xcart::app()->end();
        } else {
            echo $cart->getQuantity();
        }
    }

    public function actionProductsGet()
    {
        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();

        if ($isAjax) {
            $this->jsonResponse([
                'status' => true,
                'total' => $cart->getTotal(),
                'quantity' => $cart->getQuantity(),
                'items' => $this->getCartProductsArray(),
            ]);
            Xcart::app()->end();
        } else {
            echo $cart->getQuantity();
        }
    }

    public function getCartProductsArray()
    {
        $cart = $this->getCart();
        $items = [];

        if ($cart->getQuantity()) {
            foreach ($cart->getItems() as $item) {
                $product = $item->getObject();
                $image = null;
                if ($images = $product->getImages()) {
                    $image = $images[0]->getUrl();
                }

                $items[] = [
                    'image' => $image,
                    'name' => $product->__toString(),
                    'price' => $product->getPrice($item->getQuantity()),
                    'extended' => $item->recalculate(),
                    'quantity' => $item->getQuantity(),
                ];
            }
        }

        return $items;
    }

    protected function addInternal($uniqueId, $quantity = 1)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if (!$model->isOutOfStock()) {
            Xcart::app()->cart->add($model, $quantity, null, $this->getRequest()->post->get('data', []));

            return true;
        }

        return false;
    }
}