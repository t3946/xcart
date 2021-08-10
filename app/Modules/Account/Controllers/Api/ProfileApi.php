<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Forms\PublicProfileForm;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class ProfileApi extends FrontendController
{
    public function savePublicProfile()
    {
        /**
         * @var $user UserModel
        */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $form = new PublicProfileForm();
        $form->setInstance($user);
        $form->populate($_POST, $_FILES);

        if ($form->isValid()) {
            $form->save();
            $this->jsonResponse(["avatarUrl" => "/" . $form->getInstance()->getAttributes()["avatar_image"]]);
        } else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }
}