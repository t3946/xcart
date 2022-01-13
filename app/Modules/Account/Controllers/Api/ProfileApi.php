<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Forms\PublicProfileForm;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class ProfileApi extends Controller
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

        if ($_POST['remove_avatar'] === "true" && !$_FILES["PublicProfileForm"]) {
            $_FILES["PublicProfileForm"] = null;
            $user->setAttribute("avatar_image", null);
        }

        $form->setInstance($user);
        $form->populate($_POST, $_FILES);

        if ($form->isValid()) {
            $form->save();
            $avatar_image = $form->getInstance()->getAttributes()["avatar_image"];

            if ($avatar_image) {
                $avatar_image = "/" . $avatar_image;
            }

            $this->jsonResponse(["avatarUrl" => $avatar_image]);
        } else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }
}