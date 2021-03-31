<?php

namespace Modules\Order\Forms;

use Modules\Order\OrderModule;
use Modules\Order\Traits\AddressAttributesReplacement;
use Xcart\App\Form\Fields\CheckboxField;

class CheckoutBillingAddressForm extends CheckoutAddressForm
{

    use AddressAttributesReplacement;

    public $replacement = 'b_';


    public function getFields()
    {
        $fields = parent::getFields();
        $new_fields = [];

        foreach ( $fields as $name => $one_field ) {
            $new_name = $this->replacement . $name;
            $new_fields[ $new_name ] = $one_field;
        }

        $new_fields = array_merge($new_fields, [
            'billing_same_shipping' => [
                'class' => CheckboxField::class,
                'html' => ['class' => 'hide'],
                'fieldTemplate' => 'forms/field/checkbox/switcher.tpl',
            ]
        ]);

        return $new_fields;
    }
}