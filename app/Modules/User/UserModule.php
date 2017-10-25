<?php
namespace Modules\User;

use Fenom;
use Modules\User\Helpers\BotsHelper;
use Modules\User\Helpers\PasswordHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class UserModule extends Module
{
    public $sessionTime = 15552000;


    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorSmart("isBot", "isBot", Fenom::ACCESSOR_PROPERTY);
        $template->addAccessorSmart("sessionKey", "sessionKey", Fenom::ACCESSOR_PROPERTY);
        $template->isBot = BotsHelper::IsBot();
        $template->sessionKey = Xcart::app()->request->session->getSessionKey();
    }


    public static function getPasswordHasher()
    {
        return PasswordHelper::className();
    }
}