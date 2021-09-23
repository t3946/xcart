<?php

namespace Modules\Order\Forms;

class ShippingAddressForm extends AddressForm
{
    public $replacement = 's_';

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