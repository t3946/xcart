<?php
namespace Modules\Dashboard;

use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class DashboardModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addFunction('default_search_date', self::getDefaultSearchDate());
        $template->addModifier('max_eta_colors', function($max_eta = 0)
        {
            global $config;

            if ($max_eta > 0){

                $eta_date_x = $max_eta - ($config["backorder_decision_request"]["backorder_eta_date_x"]*60*60*24);
                $eta_date_y = $max_eta + ($config["backorder_decision_request"]["backorder_eta_date_y"]*60*60*24);

                if (time() < $eta_date_x){
                    return "#cfe2f3";
                }

                if ($eta_date_x < time() && time() < $eta_date_y){
                    return '#F4CCCC';
                }

                if (time() > $eta_date_y){
                   return "do_not_show";
                }
            }
            return '';
        });
    }


    public static function getDefaultSearchDate()
    {
        $date = new \DateTime();
        $str_now = $date->format('m/d/Y');

        $date->setTimestamp(strtotime('-31 day'));
        $str_from = $date->format('m/d/Y');

        return "{$str_from} - {$str_now}";
    }
}