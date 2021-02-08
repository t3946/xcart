<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 19.07.2018
 * Time: 15:20
 */

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
        $shippingForm = new CheckoutShippingAddressForm();
        $contactForm = new CheckoutContactInfoForm();

        $this->_shippingFields = $shippingForm->getFields();
        $this->_shippingFields[ $shippingForm->replacement . 'firstname' ][ 'html' ][ 'data-duplicate' ] = $this->getName() . '_firstname';
        $this->_contactFields = $contactForm->getFields();
    }

    public function getFieldsets()
    {
        return [
            'shipping' => array_keys( $this->_shippingFields ),
            'contact' => array_keys( $this->_contactFields ),
        ];
    }

    public function getFields()
    {
        return array_merge( $this->_shippingFields, $this->_contactFields );
    }
}