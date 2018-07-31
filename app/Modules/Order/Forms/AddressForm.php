<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Core\Forms\FrontendModelForm;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\CountryModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Main\Xcart;

abstract class AddressForm extends FrontendForm
{
    public $replacement;

    public function getFields()
    {
        $geoIp = GeoipHelper::getGeoipLocation(Xcart::app()->request->getUserIP());

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
                'label' => 'Company',
                'hint' => 'Fill in if shipping to a corporate or university address',
                'html' => [
                    'placeholder' => 'Eureka Inc.'
                ],
            ],

            'address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'required' => true,
                'hint' => 'Street address please, we don\'t ship to P.O. boxes',
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
                'value' => ($geoIp && $country = CountryModel::objects()->get(
                        [
                            'code' => $geoIp['country'] ?? '',
                        ]))
                        ? $country->name
                        : null,
				'html' => [
                    'placeholder' => $country->name ?? 'United States',
                    'class' => 'auto-complete country',
                    'autocomplete' => 'off',
                    'data-code' => $country->code ?? null
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
                    'placeholder' => $geoIp['postalCode'] ?? '08540',
                    'class' => 'auto-complete zip',
                    'autocomplete' => 'off'
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
                    'placeholder' => ($geoIp && $state = StateModel::objects()->get(
                        [
                            'code' => $geoIp['region'] ?? '',
                            'country_code' => $geoIp['country'] ?? ''
                        ]))
                        ? $state->state
                        : 'New Jersey',
                    'class' => 'auto-complete state',
                    'autocomplete' => 'off'
                ],
            ],

            'city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true,
                'html' => [
                    'placeholder' => $geoIp['city'] ?? 'Princeton',
                    'class' => 'auto-complete city',
                    'autocomplete' => 'off'
                ],

            ],
        ];
    }
}