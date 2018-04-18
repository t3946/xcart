<?php

namespace Modules\Order\Controllers;

use Modules\Core\Models\CountryModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\User\Models\AddressModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class CheckoutController extends FrontendController
{

    protected function getOrder() : OrderModel
    {
        /** @var OrderModel $order */

        $order = OrderModel::objects()->get([
            'cart_number' => Xcart::app()->cart->getCartNumber(),
        ]);

        if (!$order) {
            $this->redirect('checkout:shipping');
        }

        return $order;
    }

    public function actionShipping()
    {
        $app = Xcart::app();
        $user = $app->user;
        $cart = $app->cart;

        if (!$user) {}

        /** @var OrderModel $order */

        [$order, $is_created] = OrderModel::objects()->getOrCreate([
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

    public function actionOptions()
    {
        $app = Xcart::app();
        $user = $app->user;
        $site = $app->getModule('Sites')->getSite();

        if (!$user) {}

        $order = $this->getOrder();

        if ($app->request->getIsPost()) {
            if ($app->request->post->has('shipping_rates')) {
                if ($rates = $app->request->post->get('shipping_rates')) {
                    foreach ($rates as $d => $rateid) {
                        /** @var ShippingRateModel $rate */
                        if ($rate = ShippingRateModel::objects()->get(['rateid' => $rateid])) {
                            /** @var OrderGroupModel $group */
                            [$group] = OrderGroupModel::objects()->getOrCreate(['manufacturerid' => $d, 'orderid' => $order->orderid]);

                            $group->setAttributes([
                                'shippingid' => $rate->shippingid,
                                'shipping' => $rate->shipping->getFrontendName()
                            ]);

                            $group->save();
                        }
                    }
                }
                if ($app->request->post->has('payment_method')) {
                    if (($paymentid = $app->request->post->get('payment_method')) && $payment_method = PaymentMethodModel::objects()->get(['paymentid' => $paymentid])) {
                        $order->paymentid = $payment_method->paymentid;
                        $order->save();
                    }
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

    public function actionReview()
    {
        $app = Xcart::app();
        $user = $app->user;

        if (!$user) {}

        $order = $this->getOrder();

        echo $this->render('checkout/review.tpl', [
            'order' => $order,
        ]);

    }
}