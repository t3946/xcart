<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Core\Forms\FrontendModelForm;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\CountryModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\OrderModule;
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
                'label' => OrderModule::t('Full Name'),
                'hint' => OrderModule::t('The order will be shipped under this name'),
                'required' => true,
                'html' => [
                    'placeholder' => OrderModule::t('Albert H. Einstein')
                ]
            ],

            'company' => [
                'class' => CharField::class,
                'label' => OrderModule::t('Company'),
                'hint' => OrderModule::t('Fill in if shipping to a corporate or university address'),
                'html' => [
                    'placeholder' => OrderModule::t('Eureka Inc.')
                ],
            ],

            'address' => [
                'class' => CharField::class,
                'label' => OrderModule::t('Address'),
                'required' => true,
                'hint' => OrderModule::t("Street address please, we don't ship to P.O. boxes"),
                'html' => [
                    'placeholder' => OrderModule::t('112 Mercer Street'),
                ],
            ],

            'address_2' => [
                'class' => CharField::class,
                'label' => OrderModule::t('Address (line 2)'),
                'hint' => OrderModule::t('Apartment, suite, floor, etc.'),
                'html' => [
                    'placeholder' => OrderModule::t('Apt 1')
                ],
            ],

            'country' => [
                'class' => CharField::class,
                'label' => OrderModule::t('Country'),
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
                'label' => OrderModule::t('Zip/Postal Code'),
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
                'label' => OrderModule::t('State/Province'),
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
                'label' => OrderModule::t('City'),
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