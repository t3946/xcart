<?php

namespace Modules\Order\Validation;


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

            $filter = ['name' => $value];
            if (array_key_exists($value, CountryModel::$codes)) {
                $filter = ['code' => CountryModel::$codes[$value]];
            }

            if (!CountryModel::objects()->get($filter)) {
                $this->addError(Translate::getInstance()->t('validation', 'Is not a valid country', []));
            }
        }

        return $this->hasErrors() === false;
    }
}