<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 20.07.2018
 * Time: 17:54
 */

namespace Modules\Order\Traits;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;

trait AddressAttributesReplacement
{
    public function setAttributes(array $data)
    {
        $t_data = [];

        foreach ($data as $key=>$val)
        {
            if (\is_string($val)) {
                $t_data[$key] = trim($val);
            }
        }

        if (strpos($data[$this->replacement . 'address'], "\n")) {
            $t = explode("\n", $t_data[$this->replacement . 'address']);
            $t_data[$this->replacement . 'address'] = $t[0];
            $t_data[$this->replacement . 'address_2'] = $t[1];
        }

        if ($t_data[$this->replacement . 'state'] && $t_data[$this->replacement . 'country']) {
            /** @var StateModel $sModel */
            if ($sModel =  StateModel::objects()->get([
                'code' => $t_data[$this->replacement . 'state'],
                'country_code' =>  $t_data[$this->replacement . 'country'
                ]])) {
                $t_data[$this->replacement . 'state'] = $sModel->state;
                $state_f = $this->getField($this->replacement . 'state');
                $state_f->setAttributes(array_merge($state_f->getAttributes(), ['data-code' => $sModel->code ?? '']));
            }
        }

        if ($t_data[$this->replacement . 'country']) {
            if ($cModel =  CountryModel::objects()->get(['code' =>  $t_data[$this->replacement . 'country']])) {
                $t_data[$this->replacement . 'country'] = $cModel->name;
                $country_f = $this->getField($this->replacement . 'country');
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

        if ($data[$this->replacement . 'country']) {
            /** @var CountryModel $cModel */
            if ($cModel =  CountryModel::objects()->get(['name' => $data[$this->replacement . 'country']])) {
                $data[$this->replacement . 'country'] = $cModel->code;
            }
        }

        if ($data[$this->replacement . 'state'] && $data[$this->replacement . 'country']) {
            /** @var StateModel $sModel */
            if ($sModel =  StateModel::objects()->get([
                'state' => $data[$this->replacement . 'state'],
                'country_code' =>  $data[$this->replacement . 'country']
            ])) {
                $data[$this->replacement . 'state'] = $sModel->code;
            }
        }

        if ($data[$this->replacement . 'address'] && $data[$this->replacement . 'address_2']) {
            $data[$this->replacement . 'address'] .= "\n" . $data[$this->replacement . 'address_2'];
            unset($data[$this->replacement . 'address_2']);
        }

        return $data;
    }
}