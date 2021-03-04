<?php

namespace Modules\Order\Forms;

use Modules\Core\Behaviours\CheckoutFormDisplayBehavior;
use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\FormClearInputBehavior;
use Modules\Order\Controllers\OrderProcessController;
use Modules\Order\Helpers\OrderHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Form\Fields\RadioField;
use Xcart\App\Main\Xcart;

class CheckoutForm extends ShippingForm
{
    protected function behaviours()
    {
        return [
            'validation' => [
                'class' => ClientValidationBehavior::class,
                'enabled' => true
            ],
            'clear' => [
                'class' => FormClearInputBehavior::class,
                'enabled' => true
            ],
            'decor' => [
                'class' => CheckoutFormDisplayBehavior::class,
                'enabled' => true
            ],
        ];
    }

    protected function beforeConstruct()
    {
        $shipping_form = new CheckoutShippingAddressForm();

        $this->_shippingFields = $shipping_form->getFields();
        $this->_shippingFields[ $shipping_form->replacement . 'firstname' ][ 'html' ][ 'data-duplicate' ] = $this->getName() . '_ci_firstname';
        $this->_contactFields = ( new CheckoutContactInfoForm() )->getFields();
        $this->_billingFields = ( new CheckoutBillingForm() )->getFields();
        $this->_purchase_order_details_form = ( new CheckoutPurchaseOrderDetailsForm() )->getFields();
        $this->_purchasing_manager_form = ( new CheckoutPurchasingManagerForm() )->getFields();
        $this->_accounts_payable_form = ( new CheckoutAccountsPayableForm() )->getFields();
        $this->_pay_by_card_form = ( new PayByCardForm() )->getFields();

    }

    public function getFieldsets()
    {
        return [
            'shipping' => array_keys( $this->_shippingFields ),
            'contact' => array_keys( $this->_contactFields ),
            'billing' => array_keys( $this->_billingFields ),
            'purchase_order_details' => array_keys( $this->_purchase_order_details_form ),
            'purchasing_manager' => array_keys( $this->_purchasing_manager_form ),
            'accounts_payable' => array_keys( $this->_accounts_payable_form ),
            'pay_by_card' => array_keys( $this->_pay_by_card_form ),
            'other' => [ 'paymentid' ],
        ];
    }

    public function getFields()
    {
        $fields = array_merge(
            $this->_shippingFields,
            $this->_contactFields,
            $this->_billingFields,
            $this->_purchase_order_details_form,
            $this->_purchasing_manager_form,
            $this->_accounts_payable_form,
            $this->_pay_by_card_form,
        );

        if ( $order = OrderHelper::getCartOrder() ) {
            //get payment methods
            $site = Xcart::app()->getModule( 'Sites' )->getSite();

            $payment_methods_query = PaymentMethodModel::objects()
                ->filter( [ 'active' => 'Y', 'site__through__storefrontid' => $site->storefrontid ] )
                ->order( [ 'is_cod', 'orderby' ] );

            //only phone order for no address orders
            if ( count( OrderProcessController::getShippingRates( $order ) ) < count( Xcart::app()->cart->getItemsGroupedBy() ) ) {
                $phone_payment_id = 4;
                $payment_methods_query->filter( [ 'paymentid' => $phone_payment_id ] );
            }

            $payment_methods = $payment_methods_query->all();

            $choices = [];

            foreach ( $payment_methods as $method ) {
                $choices[ $method->paymentid ] = $method->paymentid;
            }


            $fields[ 'paymentid' ] = [
                'class' => RadioField::class,
                'choices' => $choices,
                'value' => $order->paymentid,
                'inputClass' => 'common-input-radio',
            ];
        }

        return $fields;
    }

    public function renderBegin( $params = [], $template = null )
    {
        $params[ 'action' ] = '/checkout/';
        $prefix = $this->getFormId();
        $this->onBeforeRenderBegin( $prefix, $template );

        if ( !$template ) {
            $template = $this->formBeginTemplate;
        }

        if ( empty( $template ) ) {
            return '';
        }

        return $this->renderInternal( $template, array_merge( [
            'form' => $this,
            'prefix' => $prefix
        ], $params ) );
    }
}