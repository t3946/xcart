<?php

namespace Modules\Goods\Controllers;

use Modules\Cart\CartModule;
use Modules\Cart\Components\CartItem;
use Modules\Cart\Controllers\BaseCartController;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Modules\Order\Forms\CountShippingForm;
use Modules\Order\Models\OrderModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\ShippingModule;
use Xcart\App\Main\Xcart;

class CartController extends BaseCartController
{
    public $defaultListRoute = 'cart:list';
    public $listRoute = 'cart:list';

    public function actionAdd($uniqueId, $quantity = 1)
    {
        parent::actionAdd($uniqueId, $this->getRequest()->post->get('quantity', 1));
    }

    public function actionProductsDel(): void
    {

        $cart = $this->getCart();

        if ($items = $this->getRequest()->post->get('items', [])) {

            foreach ( $items as $item) {
                $cart->removeByKey($item);
            }
        }

        $this->actionProductsGet();
    }

    public function actionProductsSet(): void
    {
        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $this->setInternal($item['id'], $item['quantity']);
            }
        }

        $this->actionProductsGet();
    }

    /**
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function actionProductsAdd(): void
    {
        if ($items = $this->getRequest()->post->get('items', [])) {
            foreach ( $items as $item) {
                $data = [];

                if ($item['options']) {
                    foreach ($item['options'] as $option) {
                        //$data[$option['o_id']] = $option['ov_id'];
                        $optionId = (int)$option['optionId'];
                        $variantId = (int)$option['variantId'];
                        $data[$optionId] = $variantId;
                    }
                }

                $this->addInternal($item['id'], $item['quantity'], $data);
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
        }
        else {
            echo $cart->getQuantity();
        }
    }

    public function actionProductsGet(): void
    {
        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getCartStateArray());
            Xcart::app()->end();
        }
        echo $this->getCart()->getQuantity();
    }

    public function getCartStateArray(): array
    {
        $cart = $this->getCart();
        $currency = Xcart::app()->getModule('Sites')->getSite()->getCurrency();

        return [
            'total' => $cart->getTotal(),
            'discount' => $cart->getDiscountSum(),
            'quantity' => $cart->getQuantity(),
            'items' => $this->getCartProductsArray(),
            'groups' => $this->getCartGroupsArray(),
            'currency' => "{$currency->symbol_prefix}{$currency}"
          //  'options' => $this->getOptions(),
        ];
    }

//    public function getOptions(){
//        $cart = $this->getCart();
//       // $group = $cart->getItemsGroupedBy();
//       // dd($cart_items);
//
//        $items = [];
//
//        //if (!$cart_items) {
//            $cart_items = $cart->getItems();
//       // }
//
//        if ($cart_items) {
//            foreach ($cart_items as $key => $item) {
//                $items[$key] = $this->getProductStructure($item, $key);
//            }
//        }
//        var_dump($items); exit;
//        //$items = $group['items'];
////        foreach ($group as $g){
////            foreach ($g['items'] as $item){
////                var_dump($item->data);
////            }
////        }
//    }

    public function getCartGroupsArray($with_items = false): array
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

    protected function getProductStructure(CartItem $item, $key): array
    {
        /** @var ProductModel $product */
        $product = $item->getObject();
        $image = null;
        if ($images = $product->getImages()) {
            $image = $images[0]->getURL();

            /** @var \Modules\Sites\Models\SiteModel $site */
            $site = $product->sites->limit(1)->get();
            $image = '//cdn.' . $site->getBaseDomain() . $image;
        }

        $price = $product->getPrice($item->getQuantity());
        $extended = $item->recalculate();
//        $discount = $item->getPrice() - $extended;
        $discount = $item->getDiscountSum();

        $options = [];
        $data = $item->data;
        if(!empty($data)) {
            $modelOptionVariant = new ProductOptionVariantModel();
            foreach ($data as $productOptionId => $variantId){
                $optionItem = $modelOptionVariant->findItem($productOptionId, $variantId);
                $options[] = [
                    'title' => $optionItem->product_option->option->title,
                    'type' => $optionItem->product_option->option->type,
                    'name' => $optionItem->variant->name,
                    'value' => $optionItem->variant->value
                ];
            }
        }

        return [
            'key' => $key,
            'image' => $image,
            'name' => $product->getFrontendName(),
            'href' => $product->getAbsoluteUrl(),
            'id' => $product->productid,
            'code' => $product->productcode,
            'price' => $price,
            'extended' => $extended,
            'quantity' => $item->getQuantity(),
            'discount' => $discount,
            'avail' => $product->avail,
            'options' => $options
        ];
    }

    /**
     * @param \Modules\Cart\Components\CartItem[]|null $cart_items
     *
     * @return array
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function getCartProductsArray($cart_items = null): array
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

    public function actionCalculateShipping()
    {

        $shippingForm = new CountShippingForm();

        if (Xcart::app()->request->getIsPost() && $shippingForm->populate(Xcart::app()->request->post)->isValid()) {

            if ($cart_groups = Xcart::app()->cart->getItemsGroupedBy()) {

                /** @var ShippingModule $shm */
                $shm = Xcart::app()->getModule('Shipping');
                foreach ($cart_groups as $g => $cart_group)
                {
                    if ($rates = $shm::getShipping($g, new OrderModel($shippingForm->getAttributes()), $cart_group)) {
                        foreach ($rates as $rate) {
                            $ship_m = $rate->shipping;
                            $result[$ship_m->getFrontendName() . ' - '. $ship_m->shipping_time] = "US$ {$rate->getShippingCharge()}";
                        }
                    }
                }

                $this->jsonResponse([
                    'type' => 'json',
                    'result' => $result
                ]);
                return;
            }
        }

        $this->display('cart/calculate_shipping.tpl', [
            'form' => $shippingForm
        ]);
    }

    protected function addInternal($uniqueId, $quantity = 1, $data = [])
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if ($model && !$model->isOutOfStock()) {
            $cart = $this->getCart();
            $item = null;
            $inCart = 0;

            if ($cart->has($model)) {
                $item = $cart->get($model);
                $inCart = $item->getQuantity();
            }

            $tq = ($model->getActualQuantity($quantity + $inCart) - $inCart);

            if ($tq) {
                if ($item) {
                    $item->setQuantity($tq + $inCart);
                    $item->setData($data);
                }
                else {
                    $cart->add($model, $tq, null, $data);
                }

                if ($tq != $quantity) {
                    return $tq;
                }

            }

            return true;
        }

        return false;
    }

    protected function setInternal($uniqueId, $quantity = 1)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['pk' => $uniqueId]);

        if ($model && !$model->isOutOfStock()) {
            $cart = $this->getCart();

            if ($cart->has($model)) {
                $item = $cart->get($model);

                $tq = $model->getActualQuantity($quantity);

                if ($item && $tq) {
                    $item->setQuantity($tq);

                    if ($tq != $quantity) {
                        return $tq;
                    }
                }

                return true;
            }
        }

        return $this->addInternal($uniqueId, $quantity);
    }
}