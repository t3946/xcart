<?php

namespace Modules\Order\Traits;


use Modules\Core\Models\CountryLangsModel;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

trait AddressAttributesReplacement
{
    public function setAttributes(array $data)
    {
        $t_data = [];

        foreach ($data as $key=>$val)
        {
            if (\is_string($val) || is_bool($val)) {
                $t_data[$key] = trim($val);
            }
        }

        $replacements = $this->replacement;
        if (!is_array($replacements)) {
            $replacements = [$replacements];
        }


        foreach ($replacements as $replacement) {
            if (strpos($data[$replacement . 'address'], "\n")) {
                $t = explode("\n", $t_data[$replacement . 'address']);
                $t_data[$replacement . 'address'] = $t[0];
                $t_data[$replacement . 'address_2'] = $t[1];
            }

            if ($t_data[$replacement . 'state'] && $t_data[$replacement . 'country']) {
                /** @var StateModel $sModel */
                if ($sModel = StateModel::objects()->get([
                    'code' => $t_data[$replacement . 'state'],
                    'country_code' => $t_data[$replacement . 'country']])) {
                    $t_data[$replacement . 'state'] = $sModel->state;
                    $state_f = $this->getField($replacement . 'state');
                    $state_f->setAttributes(array_merge($state_f->getAttributes(), ['data-code' => $sModel->code ?? '']));
                }
            }

            if ($t_data[$replacement . 'country']) {
                /** @var CountryModel $cModel */
                if ($cModel = CountryModel::objects()->get(['code' => $t_data[$replacement . 'country']])) {
                    $t_data[$replacement . 'country'] = $cModel->countryNameBySite();
                    $country_f = $this->getField($replacement . 'country');
                    $country_f->setAttributes(array_merge($country_f->getAttributes(), ['data-code' => $cModel->code ?? '']));
                }
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
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $replacements = $this->replacement;
        if (!is_array($replacements)) {
            $replacements = [$replacements];
        }

        foreach ($replacements as $replacement) {
            if ($data[$replacement . 'country']) {
                $filter = ['value' => $data[$replacement . 'country'], 'lang_id' => $site->lang->lang_id];

                if (array_key_exists(strtoupper($data[$replacement . 'country']), CountryModel::$codes)) {
                    $filter = [
                        'country_code' => CountryModel::$codes[strtoupper($data[$replacement . 'country'])],
                        'lang_id' => $site->lang->lang_id
                    ];
                }

                /** @var CountryLangsModel $cModel */
                if ($cModel = CountryLangsModel::objects()->get($filter)) {
                    $data[$replacement . 'country'] = $cModel->country_code;
                }
            }

            if ($data[$replacement . 'state'] && $data[$replacement . 'country']) {
                /** @var StateModel $sModel */
                if ($sModel = StateModel::objects()->get([
                    'state' => $data[$replacement . 'state'],
                    'country_code' => $data[$replacement . 'country']
                ])) {
                    $data[$replacement . 'state'] = $sModel->code;
                }
            }

            if ($data[$replacement . 'address'] && $data[$replacement . 'address_2']) {
                $data[$replacement . 'address'] .= "\n" . $data[$replacement . 'address_2'];
                unset($data[$replacement . 'address_2']);
            }
        }

        return $data;
    }
}