<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\OrderModule;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CharSwitcherField;
use Xcart\App\Main\Xcart;

abstract class CheckoutAddressForm extends FrontendForm
{
    public $replacement;

    public function getFields()
    {
        $geoIp = GeoipHelper::getGeoipLocation( Xcart::app()->request->getUserIP() );

        return [
            'firstname' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Full Name' ),
                'hint' => OrderModule::t( 'The order will be shipped under this name' ),
                'required' => true,
                'html' => [
                    'placeholder' => OrderModule::t( 'Albert H. Einstein' ),
                    'autocomplete' => 'new-password',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'company' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Company' ),
                'hint' => OrderModule::t( 'Fill in if shipping to a corporate or university address' ),
                'html' => [
                    'placeholder' => OrderModule::t( 'Eureka Inc.' ),
                ],
                'labelClass' => 'common-label common-label',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'labelCommentClass' => 'common-comment',
                'fieldClass' => 'checkout-field',
            ],

            'address' => [
                'class' => CharSwitcherField::class,
                'fieldTemplate' => 'forms/field/default/custom/field_switcher.tpl',
                'label' => OrderModule::t( 'Address' ),
                'required' => true,
                'hint' => OrderModule::t( "Street address please, we don't ship to P.O. boxes" ),
                'html' => [
                    'placeholder' => OrderModule::t( '112 Mercer Street' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'switcherClass' => 'address-switcher-button switcher-button_other-fields-switcher',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
                'inputClass' => 'common-input switcher-input',
            ],

            'address_2' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Address (line 2)' ),
                'hint' => OrderModule::t( 'Apartment, suite, floor, etc.' ),
                'html' => [
                    'placeholder' => OrderModule::t( 'Apt 1' ),
                ],
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'labelCommentClass' => 'common-comment',
            ],

            'country' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Country' ),
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
                'value' => ( $geoIp && $country = CountryModel::objects()->get(
                        [
                            'code' => $geoIp[ 'country' ] ?? '',
                        ] ) )
                    ? $country->name
                    : null,
                'html' => [
                    'placeholder' => $country->name ?? 'United States',
                    'class' => 'auto-complete country',
                    'data-code' => $country->code ?? null,
                    'autocomplete' => 'new-password',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'zipcode' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Zip/Postal Code' ),
                'required' => true,
                'validators' => [
                    new ZipCodeValidator()
                ],
                'html' => [
                    'placeholder' => $geoIp[ 'postalCode' ] ?? '08540',
                    'class' => 'auto-complete zip',
                    'autocomplete' => 'new-password',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'state' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'State/Province' ),
                'required' => true,
                'validators' => [
                    new StateValidator( [ 'country' => 'country' ] )
                ],
                'html' => [
                    'placeholder' => ( $geoIp && $state = StateModel::objects()->get(
                            [
                                'code' => $geoIp[ 'region' ] ?? '',
                                'country_code' => $geoIp[ 'country' ] ?? ''
                            ] ) )
                        ? $state->state
                        : 'New Jersey',
                    'class' => 'auto-complete state',
                    'autocomplete' => 'new-password',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'city' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'City' ),
                'required' => true,
                'html' => [
                    'placeholder' => $geoIp[ 'city' ] ?? 'Princeton',
                    'class' => 'auto-complete city',
                    'autocomplete' => 'new-password',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],
        ];
    }
}