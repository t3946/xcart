<?php

namespace Modules\Order\Controllers;

use Mobile_Detect;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\OrderModule;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\ShippingModule;
use Modules\User\Models\AddressModel;
use ShippingAddressForm;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class CheckoutController extends FrontendController
{

    public function beforeAction($action, $params)
    {
        parent::beforeAction($action, $params);

        if ($action != 'actionComplete' && !Xcart::app()->cart->isValid()) {
            $this->redirect('cart:list');
        }
    }

    protected function getOrder(): OrderModel
    {
        /** @var OrderModel $order */
        $order = OrderHelper::getCartOrder();

        if (!$order) {
            $this->redirect('checkout:shipping');
        }

        return $order;
    }

    public function actionShipping(): void
    {
        /** @var OrderModel $order */

        $app = Xcart::app();
        $user = $app->user;
        $cart = $app->cart;
        $errors = [];
        $shipping = null;

        if ($app->request->getIsPost()) {

            $data = $app->request->post->all();

            if (!($errors = OrderHelper::isValidShippingAddress($data))) {
                [$order, $is_created] = OrderModel::objects()->getOrCreate([
                    'cart_number' => $cart->getCartNumber(),
                ]);

                $shipping = $data['ShippingAddressForm'];
                $contact = $data['ContactInfoForm'];

                $s_state = $shipping['s_statename'];

                if (!StateModel::objects()->get(['code' => $s_state]) && $state_m = StateModel::objects()->get(['state' => $s_state, 'country_code' => $shipping['s_country']])) {
                    $s_state = $state_m->code;
                }

                if ($user && $user->id) {
                    [$address] = AddressModel::objects()->getOrCreate([
                        'user_id' => $user->id,
                        'full_name' => $shipping['s_firstname'],
                        'company' => $shipping['s_company'],
                        'address' => $shipping['s_address'],
                        'address_2' => $shipping['s_address_2'],
                        'country' => $shipping['s_country'],
                        'zip' => $shipping['s_zipcode'],
                        'state' => $s_state,
                        'city' => $shipping['s_city'],
                    ]);
                    //$address->save();
                }


                $order->setAttributes([
                    's_firstname' => $shipping['s_firstname'],
                    's_company' => $shipping['s_company'],
                    's_address' => $shipping['s_address'] . PHP_EOL . $shipping['s_address_2'],
                    's_country' => $shipping['s_country'],
                    's_zipcode' => $shipping['s_zipcode'],
                    's_state' => $s_state,
                    's_city' => $shipping['s_city'],
                    'phone' => preg_replace('/\D/S', '', $contact['phone']),
                    'phone_ext' => $contact['phone_ext'],
                    'email' => $contact['email'],
                    'firstname' => $contact['firstname'],
                    'login' => $user->login,
                    'order_prefix' => $app->getModule('Sites')->getSite()->getOrderPrefix(),
                    'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2,
                    'user_id' => $user->id,
                    'is_mobile_checkout' => (new Mobile_Detect)->isMobile()
                ]);

                if ($order->save()) {
                    if ($is_created) {
                        $app->event->trigger('order:created', ['model' => $order]);
                    }
                    $this->redirect('checkout:options');
                }
            }
        }

        $order = OrderModel::objects()->get([
            'cart_number' => $cart->getCartNumber(),
        ]);

        if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
            $this->redirect('cart:list');
        }

        if ($order) {
            [$shipping] = $order->getAddressInfo();
        }

        $this->display('checkout/shipping.tpl', [
            'order' => $order,
            'errors' => $errors,
            'address' => $shipping['address'],
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
        $errors = [];

        $order = $this->getOrder();

        if ($app->request->getIsPost()) {

            if ($order->groups->count()) {
                $order->groups->delete();
            }

            if ($order->detail_models->count()) {
                $order->detail_models->delete();
            }

            $this->internalCartChanged($order);

            $data = $app->request->post->all();

            if ($cart_groups = $cart->getItemsGroupedBy()) {
                $rates = $app->request->post->get('shipping_rates');

                $order->subtotal = $order->shipping_cost = 0;

                foreach ($cart_groups as $g => $cart_group) {

                    [$group] = OrderGroupModel::objects()->getOrCreate(['manufacturerid' => $g, 'orderid' => $order->orderid]);

                    if ($rates[$g] && ($rate = ShippingRateModel::objects()->get(['rateid' => $rates[$g]]))) {

                        /** @var ShippingRateModel[] $shipping_rates */
                        if (($shipping_rates = $ship_module::getShipping($g, $order, $cart_group)) && $shipping_rates[$rate->rateid]) {

                            $charge = $shipping_rates[$rate->rateid]->getShippingCharge();

                            $group->setAttributes([
                                'shippingid' => $rate->shippingid,
                                'shipping' => $rate->shipping->getFrontendName(),
                                'shipping_gross' => $charge,
                                'shipping_net' => $charge,
                                'total_gross' => $cart_group['subtotal'],
                                'total_net' => $cart_group['subtotal'],
                            ]);

                            $group->save();

                            $order->subtotal += $group->total_gross;
                            $order->shipping_cost += $charge;
                        }
                    }

                    foreach ($cart_group['items'] as $item) {

                        /** @var ProductModel $product */
                        $product = $item->getObject();

                        $detail = new OrderDetailModel([
                            'orderid' => $group->orderid,
                            'productid' => $product->productid,
                            'price' => $product->getPrice(),
                            'amount' => $item->getQuantity(),
                            'productcode' => $product->productcode,
                            'product' => $product->getFrontendName(),
                            'provider' => $product->provider,
                            'original_provider' => $product->original_provider,
                            'item_cost_to_us' => $product->cost_to_us,
                        ]);
                        $detail->save();
                    }
                }

                $order->total = $order->subtotal + $order->shipping_cost;
                $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2;

                $order->save();
            }

            if ($app->request->post->has('payment_method')) {
                if (($paymentid = $app->request->post->get('payment_method')) && $payment_method = PaymentMethodModel::objects()->get(['paymentid' => $paymentid])) {
                    /** @var PaymentMethodModel $payment_method */
                    $order->paymentid = $payment_method->paymentid;
                }
            }

            if ($app->request->post->has('billing_same')) {
                if ($app->request->post->get('billing_same')) {
                    $order->setAttributes([
                        'b_address' => $order->s_address,
                        'b_firstname' => $order->s_firstname,
                        'b_company' => $order->s_company,
                        'b_city' => $order->s_city,
                        'b_state' => $order->s_state,
                        'b_country' => $order->s_country,
                        'b_zipcode' => $order->s_zipcode,
                    ]);
                } else {
                    if (!($errors = OrderHelper::isValidShippingAddress($data))) {
                        $order->setAttributes($data['BillingAddressForm']);

                        $b_state = $data['BillingAddressForm']['b_statename'];
                        if (!StateModel::objects()->get(['code' => $b_state])) {
                            if ($state_m = StateModel::objects()->get(['state' => $b_state, 'country_code' => $data['BillingAddressForm']['b_country']])) {
                                $b_state = $state_m->code;
                            }
                        }

                        $order->b_state = $b_state;
                    }
                }
            }
            $order->save();

            if (!$errors) {
                $this->redirect('checkout:review');
            }
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter(['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid])
            ->order(['is_cod', 'orderby'])
            ->all();

        [$shipping_address, $billing_address] = $order->getAddressInfo();

        $this->display('checkout/options.tpl', [
            'order' => $order,
            'payment_methods' => $payment_methods,
            'errors' => $errors,
            'countries' => Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql()),
            'shipping_address' => $shipping_address,
            'billing_address' => $billing_address,
        ]);
    }

    public function actionReview(): void
    {
        $app = Xcart::app();
        $user = $app->user;

        $order = $this->getOrder();

        $this->internalCartChanged($order);

        if ($app->request->getIsPost()) {
            if ($app->request->post->has('customer_notes')) {
                $order->setAttributes([
                    'customer_notes' => trim($app->request->post->get('customer_notes')),
                ]);
                $order->save();
            }

            if ($app->request->post->has('purchase_order')) {
                if (($purchase_order = $app->request->post->get('purchase_order')) && 1 == 1) { //verify
                    /** @var OrderModel $extra */
                    [$extra] = OrderExtraModel::objects()->getOrNew(['order_id' => $order->orderid]);
                    $extra->purchase_order = $purchase_order;
                    $extra->save();
                }
            }

            $this->redirect('checkout:payment');
        }

        [$shipping_address, $billing_address] = $order->getAddressInfo();

        $this->display('checkout/review.tpl', [
            'order' => $order,
            'shipping_address' => $shipping_address,
            'billing_address' => $billing_address,
        ]);

    }

    public function actionPayment(): void
    {
        $order = $this->getOrder();

        $this->internalCartChanged($order);

        $this->redirect('payment:process', ['gateway' => strtolower($order->payment_method->frontend_processor->processor_name)]);

    }

    public function actionComplete($order_id): void
    {
        /** @var OrderModel $order */
        $app = Xcart::app();
        $user = $app->user;

        if($order = OrderModel::objects()->get(['orderid' => $order_id, 'user_id' => $user->id])) {

            [$shipping, $billing] = $order->getAddressInfo();

            $this->display('checkout/complete.tpl', [
                'order' => $order,
                'shipping_info' => $shipping,
                'billing_info' => $billing,
            ]);
        } else {
            $this->error(404);
        }
    }

    private function internalCartChanged(OrderModel $order, $route = 'checkout:options')
    {
        if (OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1 === $order->cb_status) {

            $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2;
            $order->save();

            Xcart::app()->flash->error(OrderModule::t('Cart changed: One or more items have changed!'));

            $this->redirect($route);
        }
    }
}