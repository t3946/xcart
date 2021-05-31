<?php

namespace Modules\Order\Forms;

use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\OrderModule;
use Modules\Order\Traits\AddressAttributesReplacement;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Main\Xcart;

class CheckoutBillingAddressForm extends CheckoutAddressForm
{

    use AddressAttributesReplacement;

    public $replacement = 'b_';


    public function getFields()
    {
        $geoIp = GeoipHelper::getGeoipLocation( Xcart::app()->request->getUserIP() );

        $fields = [
            'full_address' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Address' ),
                'required' => true,
                'html' => [
                    'placeholder' => OrderModule::t( '112 Mercer Street' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
                'inputClass' => 'common-input',
            ],

            'address' => [
                'class' => HiddenField::class,
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
                    'inputmode' => 'numeric',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-input',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
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
        ];

        $new_fields = [];

        foreach ( $fields as $name => $one_field ) {
            $new_name = $this->replacement . $name;
            $new_fields[ $new_name ] = $one_field;
        }

        return $new_fields;
    }
}