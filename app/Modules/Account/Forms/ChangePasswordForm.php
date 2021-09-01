<?php

namespace Modules\Account\Forms;

use Modules\User\Helpers\PasswordHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Form\Fields\CharCleanField;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\MaxLengthValidator;
use Xcart\App\Validation\MinLengthValidator;

class ChangePasswordForm extends FrontendModelForm
{
    public function getFields()
    {
        return [
            'old_password' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],

            'new_password' => [
                'class' => CharCleanField::class,
                'required' => true,
                'validators' => [
                    new MinLengthValidator(6),
                    new MaxLengthValidator(32),
                ]
            ],

            'confirm_password' => [
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
        $user = Xcart::app()->auth->getUser(true);
        $password_hash = $user->getAttribute('password');
        $attributes = $this->getAttributes();

        // проверка подлинности не пройдена
        if (!PasswordHelper::verify($attributes['old_password'], $password_hash)) {
            $this->addError('old_password', 'Wrong password');
            return false;
        }

        if ($attributes['new_password'] !== $attributes['confirm_password']) {
            $this->addError('confirm_password', 'Must be equal password field');
            return false;
        }

        return parent::isValid();
    }
}
