<?php

namespace Modules\Order\Forms;

use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;

class ShippingAddressForm extends BaseForm
{
    public function getFields()
    {
        return [
            's_firstname' => [
                'class' => CharField::class,
                'label' => "Full Name",
                'required' => true,
            ],

            's_company' => [
                'class' => CharField::class,
                'label' => "Company (optional)",
            ],

            's_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true
            ],

            's_address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
            ],

            's_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
            ],

            's_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'required' => true,
                'validators' => [
                    new ZipCodeValidator()
                ],
            ],

            's_statename' => [
                'class' => CharField::class,
                'label' => 'State/Province',
                'required' => true,
                'validators' => [
                    new StateValidator(['country' => 's_country'])
                ],
            ],

            's_city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true
            ],
        ];
    }
}