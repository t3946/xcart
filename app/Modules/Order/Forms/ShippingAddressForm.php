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
        return [
            'firstname' => [
                'class' => CharField::class,
                'label' => 'Full Name',
                'hint' => 'The order will be shipped under this name',
                'required' => true,
                'html' => [
                    'placeholder' => 'Albert H. Einstein'
                ]
            ],

            'company' => [
                'class' => CharField::class,
                'label' => 'Company <i>(optional)</i>',
                'hint' => 'Fill in if shipping to a corporate or university address',
                'html' => [
                    'placeholder' => 'Eureka Inc.'
                ],
            ],

            'address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true,
                'hint' => 'Street address please, we don\'t ship to P . O . boxes',
                'html' => [
                    'placeholder' => '112 Mercer Street',
                ],
            ],

            'address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
                'hint' => 'Apartment, suite, floor, etc.',
                'html' => [
                    'placeholder' => 'Apt 1'
                ],
            ],

            'country' => [
                'class' => CharField::class,
                'label' => 'Country',
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
                'html' => [
                    'placeholder' => 'United States',
                ],
            ],

            'zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'required' => true,
                'validators' => [
                    new ZipCodeValidator()
                ],
                'html' => [
                    'placeholder' => '08540',
                ],
            ],

            'state' => [
                'class' => CharField::class,
                'label' => 'State/Province',
                'required' => true,
                'validators' => [
                    new StateValidator(['country' => 'country'])
                ],
                'html' => [
                    'placeholder' => 'New Jersey'
                ],
            ],

            'city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true,
                'html' => [
                    'placeholder' => 'Princeton'
                ],
            ],
        ];
    }
}