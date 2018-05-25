<?php

namespace Modules\Order\Forms;

use Modules\Core\Models\StateModel;
use Modules\Core\Models\CountryModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

abstract class AddressForm extends BaseForm
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
                'value' => ($geoIp && $country = CountryModel::objects()->get(
                        [
                            'code' => $geoIp['country'] ?? '',
                        ]))
                        ? $country->name
                        : null,
				'html' => [
                    'placeholder' => $country->name ?? 'United States',
                    'class' => 'auto-complete country'
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
                    'class' => 'auto-complete zip'
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
                    'class' => 'auto-complete state'
                ],
            ],

            'city' => [
                'class' => CharField::class,
                'label' => 'City',
                'required' => true,
                'html' => [
                    'placeholder' => $geoIp['city'] ?? 'Princeton',
                    'class' => 'auto-complete city'
                ],

            ],
        ];
    }

    public function setAttributes(array $data)
    {
        $t_data = [];
        $len = $this->replacement ? \strlen($this->replacement) : 0;

        foreach ($data as $key=>$val)
        {
            if ($len && strpos($key, $this->replacement) === 0) {
                $key = substr($key, $len);
            }

            if (\is_string($val)) {
                $t_data[$key] = trim($val);
            }
        }

        if (strpos($data['address'], "\n")) {
            $t = explode("\n", $t_data['address']);
            $t_data['address'] = $t[0];
            $t_data['address_2'] = $t[1];
        }
		
        if ($t_data['state'] && $t_data['country']) {
            /** @var StateModel $sModel */
            if ($sModel =  StateModel::objects()->get(['code' => $t_data['state'], 'country_code' =>  $t_data['country']])) {
                $t_data['state'] = $sModel->state;
                $state_f = $this->getField('state');
                $state_f->setAttributes(array_merge($state_f->getAttributes(), ['data-code' => $sModel->code ?? '']));
            }
        }
		
		if ($t_data['country']) {
			if ($cModel =  CountryModel::objects()->get(['code' =>  $t_data['country']])) {
                $t_data['country'] = $cModel->name;
                $country_f = $this->getField('country');
                $country_f->setAttributes(array_merge($country_f->getAttributes(), ['data-code' => $cModel->code ?? '']));
            }
		}

        return parent::setAttributes($t_data);
    }

    public function getAttributes()
    {
        $data = parent::getAttributes();

        if ($data['country']) {
            /** @var CountryModel $cModel */
            if ($cModel =  CountryModel::objects()->get(['name' => $data['country']])) {
                $data['country'] = $cModel->code;
            }
        }
		
		if ($data['state'] && $data['country']) {
            /** @var StateModel $sModel */
            if ($sModel =  StateModel::objects()->get(['state' => $data['state'], 'country_code' =>  $data['country']])) {
                $data['state'] = $sModel->code;
            }
        }

        if ($data['address'] && $data['address_2']) {
            $data['address'] .= "\n" . $data['address_2'];
            unset($data['address_2']);
        }

        if ($this->replacement) {
            $t_data = [];
            foreach ($data as $key => $val) {
                $t_data[$this->replacement . $key] = $val;
            }
            $data = $t_data;
        }

        return $data;
    }
}