<?php

namespace Modules\Order\Forms;

use Modules\Core\Behaviours\CheckoutFormDisplayBehavior;
use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\FormClearInputBehavior;
use Modules\Order\Controllers\OrderProcessController;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\RadioField;
use Xcart\App\Main\Xcart;

class CheckoutForm extends ShippingForm
{
    public string $stripe_payment_intent;
    public string $public_key;
    public ?OrderModel $order;

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
        $this->_shippingFields[ $shipping_form->replacement . 'firstname' ][ 'html' ][ 'data-duplicate' ] = $this->getName() . '_firstname';
        $this->_contactFields = ( new CheckoutContactInfoForm() )->getFields();
        $this->_billingFields = ( new CheckoutBillingForm() )->getFields();
        $this->_purchase_order_details_form = ( new CheckoutPurchaseOrderDetailsForm() )->getFields();
        $this->_purchasing_manager_form = ( new CheckoutPurchasingManagerForm() )->getFields();
        $this->_accounts_payable_form = ( new CheckoutAccountsPayableForm() )->getFields();

        $pay_form = new PayByCardForm();
        $this->stripe_payment_intent = $pay_form->stripe_payment_intent;
        $this->public_key = $pay_form->public_key;
        $this->_pay_by_card_form = $pay_form->getFields();
        $this->order = OrderHelper::getCartOrder();

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
            [
                'billing_same_shipping' => [
                    'class' => CheckboxField::class,
                    'html' => ['class' => 'hide'],
                    'fieldTemplate' => 'forms/field/checkbox/switcher.tpl',
                ]
            ]
        );

        if ( $this->order ) {
            //get payment methods
            $site = Xcart::app()->getModule( 'Sites' )->getSite();

            $payment_methods_query = PaymentMethodModel::objects()
                ->filter( [ 'active' => 'Y', 'site__through__storefrontid' => $site->storefrontid ] )
                ->order( [ 'is_cod', 'orderby' ] );

            //only phone order for no address orders
            if ( count( OrderProcessController::getShippingRates( $this->order ) ) < count( Xcart::app()->cart->getItemsGroupedBy() ) ) {
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
                'value' => $this->order->paymentid,
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