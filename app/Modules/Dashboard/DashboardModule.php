<?php
namespace Modules\Dashboard;

use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\User\Models\UserModel;
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
    }

    public static function getAdminMenu()
    {
        /** @var UserModel $user */
        $user = Xcart::app()->user;

        if ($user && $user->usertype == 'A') {
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
                    'route' => Xcart::app()->router->url('dashboard:admin_filters'),
                ],
                [
                    'name'  => 'Filters group settings',
                    'route' => Xcart::app()->router->url('dashboard:admin_groups'),
                ],
            ];
        }

        return [
            [
                'name'  => 'Search',
                'route' => Xcart::app()->router->url('dashboard:search'),
            ],
            [
                'name'  => 'Customer care dashboard',
                'route' => Xcart::app()->router->url('dashboard:index'),
            ],
        ];
    }
}