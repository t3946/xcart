<?php


namespace Modules\Order\Validation;

use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class PhoneValidator extends Validator
{
    public function validate($value)
    {
        $regexp = '/^\+?[-()\d\s]+[^a-zA-Z]/m';

        if (!preg_match($regexp, $value)){
            $this->addError(Translate::getInstance()->t('validation', 'Is not a valid phone', []));
        }

        return $this->hasErrors() === false;
    }
}