<?php

namespace Modules\Order\Controllers;

use Modules\Core\Models\CountryModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\ShippingModule;
use Modules\User\Models\AddressModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class CheckoutController extends FrontendController
{

    protected function getOrder(): OrderModel
    {
        $cart = Xcart::app()->cart;

        if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
            $this->redirect('cart:list');
        }

        /** @var OrderModel $order */

        $order = OrderModel::objects()->get([
            'cart_number' => $cart->getCartNumber(),
        ]);

        if (!$order) {
            $this->redirect('checkout:shipping');
        }

        return $order;
    }

    public function actionShipping(): void
    {
        $app = Xcart::app();
        $user = $app->user;
        $cart = $app->cart;

        if (!$user) {}

        if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
            $this->redirect('cart:list');
        }

        /** @var OrderModel $order */

        [$order] = OrderModel::objects()->getOrCreate([
            'user_id' => $user->id,
            'cart_number' => $cart->getCartNumber(),
            'order_prefix' => $app->getModule('Sites')->getSite()->getOrderPrefix()
        ]);

        if ($app->request->getIsPost()) {
            $data = $app->request->post->get('customer');
            if (1==1) { //validation

                [$address] = AddressModel::objects()->getOrCreate([
                    'user_id' => $user->id,
                    'full_name' => $data['s_firstname'],
                    'company' => $data['s_company'],
                    'address' => $data['s_address'],
                    'address_2' => $data['s_address_2'],
                    'country' => $data['s_country'],
                    'zip' => $data['s_zipcode'],
                    'state' => $data['s_statename'],
                    'city' => $data['s_city'],
                ]);
                $address->save();

                $order->setAttributes([
                    's_firstname' => $address->full_name,
                    's_company' => $address->company,
                    's_address' => $address->address . PHP_EOL . $address->address_2,
                    's_country' => $address->country,
                    's_zipcode' => $address->zip,
                    's_state' => $address->state,
                    's_city' => $address->city,
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'firstname' => $data['firstname'],
                ]);

                if ($order->save()) {
                    $this->redirect('checkout:options');
                }
            }
        }

        echo $this->render('checkout/shipping.tpl', [
            'order' => $order,
            'countries' => Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql())
        ]);
    }

    public function actionOptions(): void
    {
        /** @var ShippingModule $ship_module */

        $app = Xcart::app();
        $user = $app->user;
        $site = $app->getModule('Sites')->getSite();
        $ship_module = Xcart::app()->getModule('Shipping');
        $cart = $app->cart;

        if (!$user) {}

        $order = $this->getOrder();

        if ($app->request->getIsPost()) {

            if ($order->groups->count()) {
                $order->groups->delete();
            }

            if ($app->request->post->has('shipping_rates')) {
                if ($rates = $app->request->post->get('shipping_rates')) {
                    foreach ($rates as $d => $rateid) {
                        /** @var ShippingRateModel $rate */
                        if ($rate = ShippingRateModel::objects()->get(['rateid' => $rateid])) {
                            /** @var OrderGroupModel $group */
                            [$group] = OrderGroupModel::objects()->getOrCreate(['manufacturerid' => $d, 'orderid' => $order->orderid]);

                            /** @var ShippingRateModel[] $shipping_rates */
                            if (($shipping_rates = $ship_module::getShipping($d, $order, $cart->getItemsGroupedBy()[$d])) && $shipping_rates[$rateid]) {

                                $charge = $shipping_rates[$rateid]->getShippingCharge();

                                $group->setAttributes([
                                    'shippingid' => $rate->shippingid,
                                    'shipping' => $rate->shipping->getFrontendName(),
                                    'shipping_gross' => $charge,
                                    'shipping_net' => $charge,
                                ]);

                                $group->save();
                            }
                        }
                    }
                }
                if ($app->request->post->has('payment_method')) {
                    if (($paymentid = $app->request->post->get('payment_method')) && $payment_method = PaymentMethodModel::objects()->get(['paymentid' => $paymentid])) {
                        /** @var PaymentMethodModel $payment_method */
                        $order->paymentid = $payment_method->paymentid;
                        $order->save();
                    }
                }
                if ($app->request->post->has('billing_same')) {
                   $order->setAttributes([
                       'b_address' => $order->s_address,
                       'b_firstname' => $order->s_firstname,
                       'b_company' => $order->s_company,
                       'b_city' => $order->s_city,
                       'b_state' => $order->s_state,
                       'b_country' => $order->s_country,
                       'b_zipcode' => $order->s_zipcode,
                   ]);
                   $order->save();
                }
            }
            $this->redirect('checkout:review');
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter(['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid])
            ->order(['is_cod', 'orderby'])
            ->all();

        echo $this->render('checkout/options.tpl', [
            'order' => $order,
            'payment_methods' => $payment_methods
        ]);
    }

    public function actionReview(): void
    {
        $app = Xcart::app();
        $user = $app->user;

        if (!$user) {}

        $order = $this->getOrder();

        if ($app->request->getIsPost()) {
            if ($app->request->post->has('customer_notes')) {
                $order->setAttributes([
                    'customer_notes' => $app->request->post->get('customer_notes'),
                    'cb_status' => OrderStatusModel::ORDER_STATUS_QUEUED
                ]);
                $order->save();
            }

            if ($app->request->post->has('purchase_order')) {
                if (($purchase_order = $app->request->post->get('purchase_order')) && 1==1) { //verify
                    /** @var OrderModel $extra */
                    [$extra] = OrderExtraModel::objects()->getOrNew(['order_id' => $order->orderid]);
                    $extra->purchase_order = $purchase_order;
                    $extra->save();
                }
            }

            $this->redirect('checkout:payment');
        }

        echo $this->render('checkout/review.tpl', [
            'order' => $order,
        ]);

    }

    public function actionPayment(): void
    {
        $app = Xcart::app();
        $order = $this->getOrder();

        $this->redirect("payment:process", ['gateway' => strtolower($order->payment_method->processor->processor_name)]);

    }
}