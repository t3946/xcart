<?php

namespace Modules\Order\Forms;


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
        if (empty($value) || !CountryModel::objects()->get(['code' => $value])) {
            $this->addError(Translate::getInstance()->t('validation', 'Is not a valid country', []));
        }

        return $this->hasErrors() === false;
    }
}