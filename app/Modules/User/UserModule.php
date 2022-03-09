<?php
namespace Modules\User;

use Modules\User\Helpers\BotsHelper;
use Modules\User\Helpers\PasswordHelper;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class UserModule extends Module
{
    // expiration session time(in seconds)
    public $EXP_TIME_S = 180 * 24 * 60 * 60;

    public static function onApplicationRun()
    {
        if (!Cli::isCli()) {
            $template = Xcart::app()->template->getRenderer();

            $template->addAccessorSmart("isBot", "isBot", $template::ACCESSOR_PROPERTY);
            $template->addAccessorSmart("sessionKey", "sessionKey", $template::ACCESSOR_PROPERTY);
            $template->isBot = BotsHelper::IsBot();
            $template->sessionKey = Xcart::app()->request->session->getSessionKey();
        }
    }
    public static function getPasswordHasher()
    {
        return PasswordHelper::class;
    }
}