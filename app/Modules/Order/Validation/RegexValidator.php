<?php

namespace Modules\Order\Validation;

use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class RegexValidator extends Validator
{
    public string $back_pattern;
    private string $front_pattern; // for validate.js
    private string $message = 'Please fill in a valid value for';
    public bool $required = true;
    public function __construct(string $name_field, string $regex_back, string $regex_front = null)
    {
        $this->setName($name_field);
        $this->back_pattern = $regex_back;
        $this->front_pattern = $regex_front;
    }

    /**
     * @inheritDoc
     */
    public function validate($value)
    {
        if (!empty(trim($value))) {
            if (!preg_match($this->back_pattern, $value, $matches)) {
                $this->addError(Translate::getInstance()->t('validation', "{$this->message} $this->name", []));
            }
        }
        return $this->hasErrors() === false;
    }

    public function jsValidateParams(): array
    {
        $validation = [];

        if ($this->required) {
            $validation = array_merge($validation, [
                'presence' => [
                    'message' => Translate::getInstance()->t('validation', '^' . "{$this->message} $this->name", [])
                ]
            ]);
        }
        if ($this->front_pattern) {
            $validation = array_merge($validation, [
                'format' => [
                    'pattern' => $this->front_pattern,
                    'message' => Translate::getInstance()->t('validation', '^' . "{$this->message} $this->name", []),
                ],
            ]);
        }
        return $validation;
    }
}