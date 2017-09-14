<?php

namespace Modules\Product\Controllers;

use Modules\Cart\CartModule;
use Modules\Cart\Components\CartItem;
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

    public function actionProductsDel()
    {
        $cart = $this->getCart();

        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $cart->removeByKey($item);
            }
        }

        $this->actionProductsGet();
    }

    public function actionProductsSet()
    {
        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $this->setInternal($item['id'], $item['quantity']);
            }
        }

        $this->actionProductsGet();
    }

    public function actionProductsAdd()
    {
        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $this->addInternal($item['id'], $item['quantity']);
            }
        }

        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();

        if ($isAjax) {
            $this->jsonResponse(array_replace_recursive(
                $this->getCartStateArray(),
                [
                     'message' => [
                         'title' => CartModule::t('Product(s) added'),
                         'type' => 'success'
                     ],
                ]));
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
            $this->jsonResponse($this->getCartStateArray());
            Xcart::app()->end();
        } else {
            echo $cart->getQuantity();
        }
    }

    public function getCartStateArray()
    {
        $cart = $this->getCart();

        return [
            'total' => $cart->getTotal(),
            'discount' => $cart->getDiscountSum(),
            'quantity' => $cart->getQuantity(),
            'items' => $this->getCartProductsArray(),
            'groups' => $this->getCartGroupsArray(),
        ];
    }

    public function getCartGroupsArray($with_items = false)
    {
        /** @var \Modules\Cart\Components\XCart $cart */
        $cart = $this->getCart();

        $groups = $cart->getItemsGroupedBy();
        $items = [];

        foreach ($groups as $gid => $group) {
            $group['id'] = $gid;
            $p_items = [];

            /** @var \Modules\Cart\Components\CartItem $item */
            foreach ($group['items'] as $key => $item)
            {
                if ($with_items) {
                    $p_items[$key] = $this->getProductStructure($item, $key);
                }
                else {
                    $p_items[] = $key;
                }
            }
            $group['items'] = $p_items;
            $items[$gid] = $group;
        }

        return $items;
    }

    protected function getProductStructure(CartItem $item, $key) {

        /** @var ProductModel $product */
        $product = $item->getObject();
        $image = null;
        if ($images = $product->getImages()) {
            $image = $images[0]->getUrl();

            /** @var \Modules\Sites\Models\SiteModel $site */
            $site = $product->sites->limit(1)->get();
            $image = '//cdn.' . $site->getBaseDomain() . $image;
        }

        $price = $product->getPrice($item->getQuantity());
        $extended = $item->recalculate();
//        $discount = $item->getPrice() - $extended;
        $discount = $item->getDiscountSum();

        return [
            'key' => $key,
            'image' => $image,
            'name' => $product->getFrontendName(),
            'id' => $product->productid,
            'code' => $product->productcode,
            'price' => $price,
            'extended' => $extended,
            'quantity' => $item->getQuantity(),
            'discount' => $discount,
        ];
    }

    /**
     * @param \Modules\Cart\Components\CartItem[]|null $cart_items
     *
     * @return array
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function getCartProductsArray($cart_items = null)
    {
        $cart = $this->getCart();
        $items = [];

        if (!$cart_items) {
            $cart_items = $cart->getItems();
        }

        if ($cart_items) {
            foreach ($cart_items as $key => $item) {
                $items[$key] = $this->getProductStructure($item, $key);
            }
        }

        return $items;
    }

    protected function addInternal($uniqueId, $quantity = 1)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if ($model && !$model->isOutOfStock()) {
            $cart = $this->getCart();
            $inCart = 0;

            if ($cart->has($model)) {
                $item = $cart->get($model);
                $inCart = $item->getQuantity();
            }

            if ( $model->avail >= ($inCart + $quantity))
            {
                $cart->add($model, $quantity);
            }
            else {
                $avail = $model->avail;

                if ($cart->has($model)) {
                    $item = $cart->get($model);
                    $avail -= $item->getQuantity();
                }

                if ($avail > 0) {
                    $cart->add($model, $avail);
                }

                return $avail;
            }

            return true;
        }

        return false;
    }

    protected function setInternal($uniqueId, $quantity)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if ($model && !$model->isOutOfStock()) {
            $cart = $this->getCart();

            if ($cart->has($model)) {
                $item = $cart->get($model);

                if ( $model->avail >= $quantity) {
                    $item->setQuantity($quantity);
                }
                else {
                    $item->setQuantity($model->avail);
                }

                return true;
            }
        }

        return false;
    }
}