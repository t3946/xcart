<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Form\Fields\CharCleanField;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\MaxLengthValidator;
use Xcart\App\Validation\MinLengthValidator;

class LoginForm extends FrontendModelForm
{
    public function getFields()
    {
        return [
            // email or phone
            'login' => [
                'class' => EmailField::class,
                'required' => true,
                'validators' => [
                    new EmailValidator(),
                ],
            ],
            'password' => [
                'class' => CharCleanField::class,
                'required' => true,
                'validators' => [
                    new MinLengthValidator(6),
                    new MaxLengthValidator(32),
                ],
            ],
            'remember_me' => [
                'class' => CheckboxField::class,
            ],
            'fingerprint' => [
                'class' => CharCleanField::class,
            ],
            'otp' => [
                'class' => CharCleanField::class,
            ],
            'rememberBrowser' => [
                'class' => CheckboxField::class,
            ],
        ];
    }

    public function getModel()
    {
        return new UserModel();
    }
}