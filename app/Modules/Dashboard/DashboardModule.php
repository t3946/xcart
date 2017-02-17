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

    public static function getAdminMenu()
    {
        return [
            [
                'name'  => 'Search',
                'route' => Xcart::app()->router->url('dashboard:search'),
            ],
            [
                'name'  => 'Customer care dashboard',
                'route' => Xcart::app()->router->url('dashboard:index'),
            ],
            [
                'name'  => 'Filters settings',
                'route' => Xcart::app()->router->url('dashboard:settings'),
            ],
        ];
    }
}