<?php

namespace Modules\Order\Forms;

use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;

class BillingAddressForm extends BaseForm
{
    public function getFields()
    {
        return [
            'b_firstname' => [
                'class' => CharField::class,
                'label' => "Full Name",
                'required' => true

            ],

            'b_company' => [
                'class' => CharField::class,
                'label' => "Company (optional)",
            ],

            'b_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true
            ],

            'b_address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
            ],

            'b_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
            ],

            'b_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'required' => true
            ],

            'b_statename' => [
                'class' => CharField::class,
                'label' => 'State/Province',
                'required' => true
            ],

            'b_city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true
            ],
        ];
    }
}