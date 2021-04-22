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
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\RadioField;
use Xcart\App\Main\Xcart;

class CheckoutForm extends ShippingForm
{
    public $replacement = ['s_', 'b_'];

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
            'other' => [ 'paymentid', 'customer_notes' ],
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

        //get payment methods
        $site = Xcart::app()->getModule('Sites')->getSite();

        $filter = ['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid];

        /** @var OrderModel $order */
        $order = $this->getInstance();

        if (!$order || !count(OrderProcessController::getShippingRates($order))) {
            $filter['paymentid'] = PaymentMethodModel::PHONE_ORDER_PAYMENT_METHOD_ID;
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter($filter)
            ->order(['is_cod', 'orderby']);

        $choices = [];

        foreach ($payment_methods as $method) {
            $choices[$method->paymentid] = $method;
        }

        $fields['paymentid'] = [
            'class' => RadioField::class,
            'choices' => $choices,
            'inputClass' => 'common-input-radio',
        ];

        $fields['customer_notes'] = [
            'class' => HiddenField::class,
        ];

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

    public function isValid()
    {
        $is_valid = parent::isValid();

        if (!$is_valid && $order = $this->getInstance()) {
            $errors = array_keys($this->getErrors());

            if ((int)$order->paymentid !== 2) {
                $po_required_fields =
                    array_filter(
                        array_merge(
                            $this->_purchase_order_details_form,
                            $this->_purchasing_manager_form,
                            $this->_accounts_payable_form
                        ), static fn($f) => (bool)$f['required'] === true
                    );
                array_map(fn($e) => $this->clearErrors($e), array_intersect($errors, array_keys($po_required_fields)));
            }
            if ((int)$order->paymentid !== 106) {
                $card_required_fields = array_filter(
                    $this->_pay_by_card_form, static fn($f) => (bool)$f['required'] === true
                );
                array_map(fn($e) => $this->clearErrors($e), array_intersect($errors,  array_keys($card_required_fields)));
            }

            return $this->hasErrors() === false;
        }
        return $is_valid;
    }
}