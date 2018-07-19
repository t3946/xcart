<?php

namespace Modules\Order\Forms;


use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\Fields\CharField;

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