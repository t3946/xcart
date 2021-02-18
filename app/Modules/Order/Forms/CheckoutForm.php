<?php

namespace Modules\Order\Forms;

use Modules\Core\Behaviours\CheckoutFormDisplayBehavior;
use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\FormClearInputBehavior;

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
        $this->_shippingFields[ $shipping_form->replacement . 'firstname' ][ 'html' ][ 'data-duplicate' ] = $this->getName() . '_firstname';
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
        ];
    }

    public function getFields()
    {
        return array_merge(
            $this->_shippingFields,
            $this->_contactFields,
            $this->_billingFields,
            $this->_purchase_order_details_form,
            $this->_purchasing_manager_form,
            $this->_accounts_payable_form,
            $this->_pay_by_card_form,
        );
    }
}