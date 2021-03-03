<?php

namespace Modules\Order\Controllers;

use Modules\Order\Forms\CheckoutForm;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Helpers\OrderLogHelper;
use Modules\Order\Models\AttentionTagModel;
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
                ] );
                $order->groups->update( [ 'cb_status' => OrderStatusModel::ORDER_STATUS_CANCELED ] );
                $order->cb_status = OrderStatusModel::ORDER_STATUS_CANCELED;
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
        foreach ( $cart_groups as $g => $cart_group ) {
            if ( $shipping_rates = $ship_module::getShipping( $g, $order, $cart_group ) ) {
                $sh_rates[ $g ] = $shipping_rates;
            }
        }

        return $sh_rates;
    }

    /**
     * get html off all shipping methods for passed address
     */
    public function getShippingMethods(): string
    {
        //address params
        $get_params = Xcart::app()->request->get->all();

        //make order
        $order = new OrderModel( [
            's_country' => $get_params[ 'country' ] ?? '',
            's_zipcode' => $get_params[ 'zipcode' ] ?? '',
            's_state' => $get_params[ 'state' ] ?? '',
            's_city' => $get_params[ 'city' ] ?? '',
        ] );

        $sh_rates = self::getShippingRates( $order );

        return $this->render( 'checkout/all_shipping_methods_one_page.tpl', [
            'order' => $order,
            'shipping_rates' => $sh_rates ?? []
        ] );
    }

    public function getPaymentMethods(): string
    {
        $site = Xcart::app()->getModule( 'Sites' )->getSite();

        $payment_methods = PaymentMethodModel::objects()
            ->filter( [ 'active' => 'Y', 'site__through__storefrontid' => $site->storefrontid ] )
            ->order( [ 'is_cod', 'orderby' ] )
            ->all();

        $field_sets = ( new CheckoutForm() )->getFieldsets();
        $fields = ( new CheckoutForm() )->getFieldsInit();

        foreach ( $field_sets as $set_name => $set ) {
            foreach ( $set as $key => $field_name ) {
                $set[ $key ] = $fields[ $field_name ];
            }

            $field_sets[ $set_name ] = $set;
        }

        return $this->render( 'checkout/payment_methods_one_page.tpl', [
            'payment_methods' => $payment_methods,
            'fieldsets' => $field_sets,
        ] );
    }

    public function checkoutUpdate(): void
    {
        if ( $order = OrderHelper::getCartOrder() ) {
            $response = OrderHelper::getOrderInfo( $order );
        } else {
            $order = new OrderModel();
        }

        $form = new CheckoutForm();
        $form->setInstance( $order );
        $form->populate( Xcart::app()->request->post );
        $form->setModelAttributes( $form->getAttributes() );
        if ( $model = $form->getInstance() ) {
            $model->save();
        }

        if ( 1 ) {
            $response[ 'templates' ][ 'payment_methods' ] = $this->getPaymentMethods();
        }

        if ( 1 ) {
            $response[ 'templates' ][ 'shipping_methods' ] = $this->getShippingMethods();
        }

        $this->jsonResponse( $response ?? [] );
    }
}