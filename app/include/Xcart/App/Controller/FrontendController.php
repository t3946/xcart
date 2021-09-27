<?php

namespace Xcart\App\Controller;

use Modules\Meta\Components\MetaTrait;
use Xcart\App\Helpers\SmartProperties;
use Modules\Account\Controllers\AccountController;

class FrontendController extends Controller
{
    use MetaTrait, SmartProperties;

    public function beforeAction($action, $params)
    {
        AccountController::provideAccountData();
    }
}