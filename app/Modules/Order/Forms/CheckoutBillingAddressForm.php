<?php

namespace Modules\Order\Forms;

use Modules\Order\Traits\AddressAttributesReplacement;

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

        return $new_fields;
    }
}