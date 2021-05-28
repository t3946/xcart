<?php

namespace Modules\Order\Controllers;

use Modules\Order\Forms\CheckoutForm;
use Modules\Order\Forms\PayByCardForm;
use Modules\Order\Helpers\CheckoutHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Helpers\OrderLogHelper;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class OrderProcessController extends FrontendController
{
    public function cancel( $order_id, $slug )
    {
        /** @var OrderModel $order */
        if ( $order = OrderModel::objects()->get( [ 'orderid' => $order_id ] ) ) {
            if ( $order->getOrderHash() === $slug && $order->cb_status === OrderStatusModel::ORDER_STATUS_UNPAID ) {
                $this->display( 'confirmation/confirmation.tpl', [
                    'model' => $order,
                    'h1' => "Order # {$order->getOrderNumber()} has been deleted from our system.",
                    'content' => "You won't receive any further communication from us.<br/>Have a lovely day!"
                ]);
                $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_FAILED]);
                $order->cb_status = OrderStatusModel::ORDER_STATUS_FAILED;
                $order->save();
                ( new OrderLogModel( [
                    'orderid' => $order->orderid,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => 'Abandoned: The order has been canceled',
                ] ) )->save();
                OrderInvoiceHelper::sendOrderStatusNotification( $order, false );
            }
            else {
                $this->error( 404 );
            }
        }
    }

    public function continue( $order_id, $slug )
    {
        /** @var OrderModel $order */
        if ( $order = OrderModel::objects()->get( [ 'orderid' => $order_id ] ) ) {
            if ( $order->cb_status === OrderStatusModel::ORDER_STATUS_UNPAID && $order->getOrderHash() === $slug ) {
                if ( $this->getRequest()->getIsPost() ) {
                    if ( $message = $this->getRequest()->post->get( 'message' ) ) {
                        OrderLogHelper::sendOrderNote( $order, $message, AttentionTagModel::RESUME_ORDER_TAG );
                    }
                    $this->redirect( 'order:success' );
                }

                $this->display( 'confirmation/confirmation.tpl', [
                    'model' => $order,
                    'sendMessage' => true,
                    'h1' => "Thank you for your decision to continue with your order # {$order->getOrderNumber()}",
                    'content' => "We'll get back to you shortly.<br/>Have a lovely day!"
                ] );
                $message = 'Customer would like to continue with the order!';
                OrderLogHelper::sendOrderNote( $order, $message, AttentionTagModel::RESUME_ORDER_TAG );
                $order->groups->update( [ 'cb_status' => OrderStatusModel::ORDER_STATUS_QUEUED ] );
                $order->cb_status = OrderStatusModel::ORDER_STATUS_QUEUED;
                $order->save();

            }
            else {
                $this->error( 404 );
            }
        }
    }

    public function success()
    {
        $this->display( 'confirmation/success.tpl', [
            'h1' => 'Thank you for your message!',
        ] );
    }

    /**
     * get shipping methods by order
     * @param OrderModel $order
     * @return array
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function getShippingRates( OrderModel $order ): array
    {
        //app
        $app = Xcart::app();

        if ( !$app ) {
            return [];
        }

        //cart
        $cart = $app->cart;
        $cart_groups = $cart->getItemsGroupedBy();

        if ( !$cart_groups ) {
            return [];
        }

        $ship_module = $app->getModule( 'Shipping' );

        $sh_rates = [];
        foreach ( $cart_groups as $dx_id => $group ) {
            if ( $shipping_rates = $ship_module::getShipping( $dx_id, $order, $group ) ) {
                $sh_rates[ $dx_id ] = $shipping_rates;
            }
        }

        return $sh_rates;
    }

    /**
     * get html off all shipping methods for passed address
     */
    public function getShippingMethods($form): string
    {
        $order = $form->getInstance();
        $sh_rates = self::getShippingRates( $order );
        return $this->render( 'checkout/all_shipping_methods_one_page.tpl', [
            'order' => $order,
            'shipping_rates' => $sh_rates ?? [],
            'silent' => true,
        ] );
    }

    public function getPaymentMethods($form, $only_phone_order = false): string
    {
        $order = $form->getInstance();

        $site = Xcart::app()->getModule('Sites')->getSite();

        $filter = ['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid];
        if ($only_phone_order) {
            $filter['paymentid'] = PaymentMethodModel::PHONE_ORDER_PAYMENT_METHOD_ID;
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter($filter)
            ->order(['is_cod', 'orderby'])
            ->all();

        return $this->render( 'checkout/payment_methods_one_page.tpl', [
            'checkout_form' => $form,
            'order' => $order,
            'payment_methods' => $payment_methods
        ] );
    }

    public function checkoutUpdate(): void
    {
        $post = $this->getRequest()->post;
        $cart = Xcart::app()->cart;

        $order = OrderHelper::getCartOrder();

        if ($post->has('uid') && $post->has('quantity')) {
            $cart_key = $post->get('uid');
            $quantity = $post->get('quantity');
            $quantity ? $cart->updateQuantityByKey($cart_key, $quantity) :  $cart->removeByKey($cart_key);
            if ($order) {
                CheckoutHelper::updateOrderGroupsFromCart($order, $cart);
                $shipping_rates = self::getShippingRates($order);
                CheckoutHelper::updateOrderShippingRates($order, $shipping_rates);
                CheckoutHelper::updateOrderTotalValues($order);
            }
        }
        
        if (!$order) {
            return;
        }

        if ($post->has('shipping_rates')) {
            $shipping_rates = self::getShippingRates($order);
            foreach ($post->get('shipping_rates') as $rate) {
                CheckoutHelper::updateOrderShippingRates($order, $shipping_rates, $rate, false);
            }
        }

        $order->save();

        $form = new CheckoutForm();
        $form->setInstance( $order );
        $form->populate( $post );
        $form->setModelAttributes( $form->getAttributes() );

        $form->setAttributes($order->extra_model->purchase_order ?? []);

        /** @var OrderModel $order */
        $order = $form->getInstance();

        $response = [];

        $response[ 'templates' ] = [];

        if (isset($_POST[ 'CheckoutForm' ]['organization_name'])
            || isset($_POST[ 'CheckoutForm' ]['pm_firstname'])
            || isset($_POST[ 'CheckoutForm' ]['pm_phone'])
            || isset($_POST[ 'CheckoutForm' ]['pm_phone_ext'])
            || isset($_POST[ 'CheckoutForm' ]['pm_track_sms'])
            || isset($_POST[ 'CheckoutForm' ]['pm_email'])
            || isset($_POST[ 'CheckoutForm' ]['pm_fax'])
            || isset($_POST[ 'CheckoutForm' ]['ap_firstname'])
            || isset($_POST[ 'CheckoutForm' ]['ap_phone'])
            || isset($_POST[ 'CheckoutForm' ]['ap_phone_ext'])
            || isset($_POST[ 'CheckoutForm' ]['ap_track_sms'])
            || isset($_POST[ 'CheckoutForm' ]['ap_email'])
            || isset($_POST[ 'CheckoutForm' ]['ap_fax'])
        ) {
            $po_data = [];
            $po_field_sets = ['purchase_order_details', 'purchasing_manager', 'accounts_payable'];
            foreach ($po_field_sets as $field_set) {
                foreach ($form->getFieldsets()[$field_set] as $field) {
                    if (isset($_POST[ 'CheckoutForm' ][$field])) {
                        $po_data[$field] = $form->getField($field)->getValue();
                    }
                }
            }
            [$extra] = OrderExtraModel::objects()->getOrNew(['order_id' => $order->orderid]);
            $extra->purchase_order = array_merge($extra->purchase_order ?? [], $po_data);
            $extra->save();
        }

        if ( isset($_POST['CheckoutForm']['billing_same_shipping']) ) {
            CheckoutHelper::updateBillingDetails($order);
        }

        if ( isset( $_POST[ 'CheckoutForm' ][ 's_company' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_firstname' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_address' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_address_2' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_country' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_zipcode' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_state' ] )
            || isset( $_POST[ 'CheckoutForm' ][ 's_city' ] )
            || ($post->has('uid') && $post->has('quantity'))
        ) {
            CheckoutHelper::updateBillingDetails($order);
            
            if (!isset($shipping_rates)) {
                $shipping_rates = self::getShippingRates($order);
            }

            CheckoutHelper::updateOrderShippingRates($order, $shipping_rates);

            $order->save();

            //TODO need to refactoring
            $pay_form = new PayByCardForm();
            $form->stripe_payment_intent = $pay_form->stripe_payment_intent;

            $form->setInstance($order);

            $response[ 'templates' ][ 'payment_methods' ] = $this->getPaymentMethods($form, false);
            $response[ 'templates' ][ 'shipping_methods' ] = $this->getShippingMethods($form);
        }

        $response = array_merge($response, OrderHelper::getOrderInfo($order));

        $response['payment_intent'] = $form->stripe_payment_intent;

        $order->save();

        $this->jsonResponse( $response ?? [] );
    }
}