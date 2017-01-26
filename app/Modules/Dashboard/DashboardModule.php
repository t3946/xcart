<?php
namespace Modules\Dashboard;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class DashboardModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addFunction('default_search_date', function()
        {
            $date = new \DateTime();
            $str_now = $date->format('m/d/Y');

            $date->setTimestamp(strtotime('-31 day'));
            $str_from = $date->format('m/d/Y');

            return "{$str_from} - {$str_now}";
        });
    }
}