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
            if (!CountryModel::objects()->get(['name' => $value])) {
                $this->addError(Translate::getInstance()->t('validation', 'Is not a valid country', []));
            }
        }

        return $this->hasErrors() === false;
    }
}