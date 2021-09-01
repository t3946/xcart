<?php

namespace Modules\Order\Validation;


use Xcart\App\Validation\Validator;

class ZipCodeValidator extends Validator
{

    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        return true;
    }
}