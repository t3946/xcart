<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Forms\EditEmailForm;
use Modules\Account\Forms\EditNameForm;
use Modules\Account\Forms\EditPhoneForm;
use Modules\Account\Forms\ChangePasswordForm;
use Modules\User\Helpers\PasswordHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class LoginAndSecuritiesApi extends Controller
{
    /**
     * edit current user
     */
    private function editUser($form): array
    {
        /**
         * @var $user UserModel
         */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return [];
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $form->setInstance($user);
        $form->populate($data);

        if ($form->isValid()) {
            $form->setInstance($user);
            $form->populate($data);
            $form->save();

            return ['user' => $user->toArray()];
        } else {
            return ['errors' => $form->getErrors()];
        }
    }

    public function editName()
    {
        $this->jsonResponse($this->editUser(new EditNameForm()));
    }

    public function editEmailAddress()
    {
        $this->jsonResponse($this->editUser(new EditEmailForm()));
    }

    public function editPhoneNumber()
    {
        $this->jsonResponse($this->editUser(new EditPhoneForm()));
    }

    public function editPassword()
    {
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $form = new ChangePasswordForm();
        $data = json_decode(file_get_contents('php://input'), true);
        $form->setInstance($user);
        $form->populate($data);

        if ($form->isValid()) {
            $attributes = $form->getAttributes();
            $new_password_hash = PasswordHelper::hash($attributes['new_password']);
            $user->setAttribute('password', $new_password_hash);
            $user->save();

            $this->jsonResponse(['user' => $user->toArray()]);
        } else {
            $this->jsonResponse(['errors' => $form->getErrors()]);
        }
    }
}
