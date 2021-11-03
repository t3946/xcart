<?php

namespace Xcart\App\Validation;

use Modules\Order\OrderModule;
use Modules\Translate\TranslateModule;
use Xcart\App\Translate\Translate;

/**
 * Class RequiredValidator
 * @package Mindy\Validation
 */
class RequiredValidator extends Validator
{
    /**
     * @var string
     */
    public $message;

    public function __construct($message = null)
    {
        $this->message = OrderModule::t("Can't be empty");
        if (!empty($message)) {
            $this->message = $message;
        }
    }

    public function validate($value)
    {
        if (is_null($value) || $value === "" || (is_array($value) && $value === [])) {
            $this->addError(Translate::getInstance()->t('validation', $this->message, [
                '{name}' => $this->getName()
            ]));
        }

        return $this->hasErrors() === false;
    }

    public function jsValidateParams()
    {
        return [
            'presence' => [
                'message' => Translate::getInstance()->t('validation', '^' . $this->message, [])
            ],
        ];
    }
}

