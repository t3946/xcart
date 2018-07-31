<?php


namespace Modules\Order\Validation;

use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class PhoneValidator extends Validator
{
    private $message = 'Is not a valid phone';

    public function validate($value)
    {
        if(!empty(trim($value))) {

            $regexp = '/^\+?[-()\d\s]+$/m';

            if (!preg_match($regexp, $value)){
                $this->addError(Translate::getInstance()->t('validation', $this->message, []));
            }

        }

        return $this->hasErrors() === false;
    }

    public function jsValidateParams()
    {
        return [
            'format' => [
                'pattern' => "^\+?[-()\d\s]*$",
                'flags' => "im",
                'message' => Translate::getInstance()->t('validation', '^' . $this->message, [])
            ]
        ];
    }
}