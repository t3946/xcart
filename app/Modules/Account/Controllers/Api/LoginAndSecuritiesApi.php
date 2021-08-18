<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Forms\EditNameForm;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class LoginAndSecuritiesApi extends FrontendController
{
    public function editName() {
        /**
         * @var $user UserModel
         */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $form = new EditNameForm();
        $form->setInstance($user);
        $form->populate($json);

        if ($form->isValid()) {
            $form->setInstance($user);
            $form->populate($json);
            $form->save();
            $this->jsonResponse(['user' => $user->toArray()]);
        } else {
            $this->jsonResponse(['errors' => $form->getErrors()]);
        }
    }

    public function editEmailAddress() {
        dd($_GET);
    }

    public function editPhoneNumber() {
        dd($_GET);
    }

    public function editPassword() {
        dd($_POST);
    }
}
