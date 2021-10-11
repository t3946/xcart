<?php

namespace Xcart\App\Controller;

use Modules\Meta\Components\MetaTrait;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Helpers\SmartProperties;
use Modules\Account\Controllers\AccountController;
use Xcart\App\Main\Xcart;

class FrontendController extends Controller
{
    use MetaTrait, SmartProperties;

    public function beforeAction($action, $params)
    {
        AccountController::provideAccountData();
    }

    protected function getUser(): UserModel
    {
        /**
         * @var $user UserModel
         */
        return Xcart::app()->auth->getUser(true);
    }
}