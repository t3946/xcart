<?php


namespace Modules\Order\Validation;

use Modules\Translate\TranslateModule;
use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class PhoneValidator extends Validator
{
    private $message = 'Phone number is invalid';

    public function __construct($message = null)
    {
        $this->message = TranslateModule::t('Phone number is invalid');
        if (!empty($message)) {
            $this->message = $message;
        }
    }

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
                'pattern' => "\(\d{3}\)\s\d{3}-\d{4}$",
                'flags' => "im",
                'message' => Translate::getInstance()->t('validation', '^' . $this->message, [])
            ]
        ];
    }
}