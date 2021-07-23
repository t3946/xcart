<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Form\Fields\CharCleanField;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\MaxLengthValidator;
use Xcart\App\Validation\MinLengthValidator;

class RegistrationForm extends FrontendModelForm
{
    public function getFields()
    {
        return [
            'name' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
            'email' => [
                'class' => EmailField::class,
                'required' => true,
                'validators' => [
                    new EmailValidator(),
                ]
            ],
            'password' => [
                'class' => CharCleanField::class,
                'required' => true,
                'validators' => [
                    new MinLengthValidator(6),
                    new MaxLengthValidator(32),
                ]
            ],
            'password_confirm' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
        ];
    }

    public function getModel()
    {
        return new UserModel();
    }

    public function isValid()
    {
        $is_valid = parent::isValid();

        $attributes = $this->getAttributes();

        if ($attributes['password'] !== $attributes['password_confirm']) {
            $this->addError('password_confirm', 'Must be equal password field');
        }

        return $is_valid;
    }
}
