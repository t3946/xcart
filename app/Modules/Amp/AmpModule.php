<?php
namespace Modules\Amp;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class AmpModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

    }

}