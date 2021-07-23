<?php

namespace Modules\Account\Controllers;

use Modules\Account\Forms\LoginForm;
use Modules\Account\Forms\RegistrationForm;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountController extends FrontendController
{
    public function actionIndex()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            StorageHelper::push($user->toArray(), null, 'user');
        }

        $this->display('account/base.tpl');
    }

    public function register()
    {
        $this->actionIndex();
    }

    public function login()
    {
        $this->actionIndex();
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
        $this->actionIndex();
    }
}