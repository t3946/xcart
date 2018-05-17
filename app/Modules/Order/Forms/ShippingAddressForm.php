<?php

namespace Modules\Order\Forms;

use Modules\Dashboard\Sqls\SearchSql;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\Connection;

class ShippingAddressForm extends BaseForm
{
    public function getFields()
    {
        return [
            's_firstname' => [
                'class' => CharField::class,
                'label' => 'Full Name',
                'hint' => 'The order will be shipped under this name',
                'required' => true,
                'html' => [
                    'placeholder' => 'Albert H. Einstein'
                ]
            ],

            's_company' => [
                'class' => CharField::class,
                'label' => 'Company <i>(optional)</i>',
                'hint' => 'Fill in if shipping to a corporate or university address',
                'html' => [
                    'placeholder' => 'Eureka Inc.'
                ],
            ],

            's_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true,
                'hint' => 'Street address please, we don\'t ship to P . O . boxes',
                'html' => [
                    'placeholder' => '112 Mercer Street',
                ],
            ],

            's_address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
                'hint' => 'Apartment, suite, floor, etc.',
                'html' => [
                    'placeholder' => 'Apt 1'
                ],
            ],

            's_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
                'choices' => function() {
                    $result = ['' => ''];
                    foreach (Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql()) as $item) {
                        $result[$item['id']] = $item['text'];
                    }

                    return $result;
                }
            ],

            's_zipcode' => [
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

            's_state' => [
                'class' => CharField::class,
                'label' => 'State/Province',
                'required' => true,
                'validators' => [
                    new StateValidator(['country' => 's_country'])
                ],
                'html' => [
                    'placeholder' => 'New Jersey'
                ],
            ],

            's_city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true,
                'html' => [
                    'placeholder' => 'Princeton'
                ],
            ],
        ];
    }

    public function setAttributes(array $data)
    {
        if (strpos($data['s_address'], "\n")) {
            $t = explode("\n", $data['s_address']);
            $data['s_address'] = $t[0];
            $data['s_address_2'] = $t[1];
        }

        return parent::setAttributes($data);
    }
}