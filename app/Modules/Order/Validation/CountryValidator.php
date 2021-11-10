<?php

namespace Modules\Order\Validation;


use Modules\Core\Models\CountryLangsModel;
use Modules\Core\Models\CountryModel;
use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class CountryValidator extends Validator
{

    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        if(!empty($value)) {

            $filter = ['value' => $value];
            if (array_key_exists(strtoupper($value), CountryModel::$codes)) {
                $filter = ['country_code' => CountryModel::$codes[strtoupper($value)]];
            }

            if (!CountryLangsModel::objects()->get($filter)) {
                $this->addError(Translate::getInstance()->t('validation', 'Is not a valid country', []));
            }
        }

        return $this->hasErrors() === false;
    }
}