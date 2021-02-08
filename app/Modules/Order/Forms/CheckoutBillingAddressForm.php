<?php

namespace Modules\Order\Forms;

use Modules\Order\Traits\AddressAttributesReplacement;

class BillingAddressForm extends AddressForm
{

    use AddressAttributesReplacement;

    public $replacement = 'b_';


    public function getFields()
    {
        $fields = parent::getFields();
        $newFields = [];

        foreach ($fields as $name => $oneField) {
            $newName = $this->replacement . $name;
            $newFields[$newName] = $oneField;
        }

        return $newFields;
    }
}