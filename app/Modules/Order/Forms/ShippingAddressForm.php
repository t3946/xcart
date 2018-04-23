<?php

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
                'class' => CharField::className(),
                'label' => "Full Name",

            ],

            's_company' => [
                'class' => CharField::className(),
                'label' => "Companu (optional)",
            ],

            's_address' => [
                'class' => CharField::className(),
                'label' => 'Address',
            ],

            's_address_2' => [
                'class' => CharField::className(),
                'label' => 'Address',
            ],

            's_country' => [
                'class' => DropDownField::className(),
                'label' => 'Country',
            ],

            's_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
            ],

            's_statename' => [
                'class' => CharField::className(),
                'label' => 'State/Province',
            ],

            's_city' => [
                'class' => CharField::className(),
                'label' => 'City'
            ],

            'firstname' => [
                'class' => CharField::className(),
                'label' => 'fullname'
            ],

            'phone' => [
                'class' => NumberField::className(),
                'label' => 'Phone'
            ],

            'phone_ext' => [
                'class' => NumberField::className(),
                'label' => 'ext'
            ],

            'email' => [
                'class' => EmailField::className(),
                'label' => 'Email'
            ],
        ];
    }
}