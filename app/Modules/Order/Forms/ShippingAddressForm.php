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
                'required' => true

            ],

            's_company' => [
                'class' => CharField::class,
                'label' => "Companu (optional)",
            ],

            's_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true
            ],

            's_address_2' => [
                'class' => CharField::class,
                'label' => 'Address',
            ],

            's_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'required' => true
            ],

            's_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'required' => true
            ],

            's_statename' => [
                'class' => CharField::className(),
                'label' => 'State/Province',
                'required' => true
            ],

            's_city' => [
                'class' => CharField::className(),
                'label' => 'City',
                'required' => true
            ],

            'firstname' => [
                'class' => CharField::className(),
                'label' => 'fullname',
                'required' => true
            ],

            'phone' => [
                'class' => NumberField::className(),
                'label' => 'Phone',
                'required' => true
            ],

            'phone_ext' => [
                'class' => NumberField::className(),
                'label' => 'ext'
            ],

            'email' => [
                'class' => EmailField::className(),
                'label' => 'Email',
                'required' => true
            ],
        ];
    }
}