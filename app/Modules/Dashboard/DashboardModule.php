<?php
namespace Modules\Dashboard;

use Modules\Dashboard\Helpers\NoticeTestCheckout;
use Modules\Sites\SitesModule;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class DashboardModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addModifier('max_eta_colors', function($max_eta = 0)
        {
            $config = $GLOBALS['config'];

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

        $template->addModifier('get_filtered', function($models, $gid)
        {
            $t_models = [];
            foreach ($models as $model)
            {
                if (is_null($gid) && empty($model->group_id)) {
                    $t_models[] = $model;
                }
                elseif ($model->group_id == $gid) {
                    $t_models[] = $model;
                }
            }
            return $t_models;
        });

        $template->addFunction('orders_test_checkout', function()
        {
            if (NoticeTestCheckout::test()) {
                return Xcart::app()->template->render('dashboard/test_checkout_message.tpl', []);
            }
        });
    }

    public static function getAdminMenu()
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        return [
            [
                'icon' => 'fa fa-search',
                'name' => 'Search',
                'route' => $router->url('dashboard:search'),
            ],
            [
                'icon' => 'fa fa-handshake-o',
                'name' => 'Customer care dashboard',
                'route' => $router->url('dashboard:index'),
                'items' => ($user && $user->getIsSuperuser()) ? [
                    [
                        'icon' => 'fa fa-pencil-square-o',
                        'name' => 'Filters settings',
                        'route' => $router->url('dashboard:admin_filters'),
                    ],
                    [
                        'icon' => 'fa fa-pencil-square-o',
                        'name' => 'Filters group settings',
                        'route' => $router->url('dashboard:admin_groups'),
                    ],
                ] : false,
            ],
        ];
    }
}