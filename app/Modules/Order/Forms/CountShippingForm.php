<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 31.05.2018
 * Time: 14:40
 */

namespace Modules\Order\Forms;

use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Order\OrderModule;
use Modules\Order\Validation\CountryValidator;
use Modules\Order\Validation\StateValidator;
use Modules\Order\Validation\ZipCodeValidator;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CharField;

class CountShippingForm extends BaseForm
{

    public $replacement = 's_';

    public function getFields()
    {
        return [

            'country' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Country'),
                'required' => true,
                'validators' => [
                    new CountryValidator()
                ],
                'value' => null,
                'html' => [
                    'placeholder' => 'United States',
                    'class' => 'auto-complete country',
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
                    'placeholder' => '08540',
                    'class' => 'auto-complete zip',
                    'autocomplete' => 'new-password'
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
                    'placeholder' => 'New Jersey',
                    'class' => 'auto-complete state',
                    'autocomplete' => 'new-password'
                ],
            ],

            'city' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('City'),
                'required' => true,
                'html' => [
                    'placeholder' => 'Princeton',
                    'class' => 'auto-complete city',
                    'autocomplete' => 'new-password'
                ],

            ],
        ];
    }

    /**
     * @param array $data
     * @return $this
     * @throws \Xcart\App\Exceptions\InvalidConfigException
     */
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


    /**
     * @return array
     */
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