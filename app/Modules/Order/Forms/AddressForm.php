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
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Main\Xcart;

abstract class AddressForm extends FrontendForm
{
    public $replacement;

    public function getFields()
    {
        $geoIp = GeoipHelper::getGeoipLocation(Xcart::app()->request->getUserIP());
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $ar_fields = [
            'firstname' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Full Name'),
                'hint' => OrderModule::t('The order will be shipped under this name'),
                'required' => true,
                'html' => [
                    'placeholder' => OrderModule::t('Albert H. Einstein'),
                    'autocomplete' => 'new-password'
                ]
            ],

            'company' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Company'),
                'hint' => OrderModule::t('Fill in if shipping to a corporate or university address'),
                'html' => [
                    'placeholder' => OrderModule::t('Eureka Inc.')
                ],
            ],

            'address' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Address'),
                'required' => true,
                'hint' => OrderModule::t("Street address please, we don't ship to P.O. boxes"),
                'html' => [
                    'placeholder' => OrderModule::t('112 Mercer Street'),
                ],
            ],

            'address_2' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Address (line 2)'),
                'hint' => OrderModule::t('Apartment, suite, floor, etc.'),
                'html' => [
                    'placeholder' => OrderModule::t('Apt 1')
                ],
            ],

            'country' => [
                'class' => CharCleanField::class,
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
                    'placeholder' => $country->name ?? OrderModule::t('Example country'),
                    'class' => 'auto-complete country',
                    'data-code' => $country->code ?? null,
                    'autocomplete' => 'new-password'
                ],

            ],

            'zipcode' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Zip/Postal Code'),
                'required' => true,
                'validators' => [
                    new ZipCodeValidator()
                ],
                'html' => [
                    'placeholder' => $geoIp['postalCode'] ?? OrderModule::t('Example zipcode'),
                    'class' => 'auto-complete zip',
                    'autocomplete' => 'new-password',
                    'inputmode' => 'numeric',
                ],
            ],

            'state' => [
                'class' => CharCleanField::class,
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
                        : OrderModule::t('Example state'),
                    'class' => 'auto-complete state',
                    'autocomplete' => 'new-password'
                ],
            ],

            'city' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('City'),
                'required' => true,
                'html' => [
                    'placeholder' => $geoIp['city'] ?? OrderModule::t('Example city'),
                    'class' => 'auto-complete city',
                    'autocomplete' => 'new-password'
                ],

            ],
        ];
        if (!$site->country_model->is_many_line_addresses) {
            unset($ar_fields['address_2']);
        }
        return $ar_fields;
    }
}