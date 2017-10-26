<?php
namespace Modules\AMP;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class AMPModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

    }

}