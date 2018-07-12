<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 11.07.2018
 * Time: 17:15
 */

namespace Xcart\App\Validation;


use Xcart\App\Translate\Translate;

class NumberValidator extends Validator
{
    /**
     * @var string
     */
    public $message = "Must be numeric";

    public function __construct($message = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }
    }

    public function validate($value)
    {
        if (!preg_match('/^\d*$/', $value, $matches)) {
            $this->addError(Translate::getInstance()->t('validation', $this->message, []));
        }


        return $this->hasErrors() === false;
    }

    public function jsValidateParams()
    {
        return [
            'format' => [
                'pattern' => "^\d*$",
                'flags' => "im",
                'message' => Translate::getInstance()->t('validation', '^' . $this->message, [])
            ]
        ];
    }
}