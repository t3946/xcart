<?php
namespace Modules\Reports;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class ReportsModule extends Module
{
    public static function getAdminMenu()
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        return [
            [
                'icon' => 'fa fa-file-text-o',
                'name' => 'Order reports',
                'route' => $router->url('reports:index'),
                'items' => ($user && $user->getIsSuperuser()) ? [
                    [
                        'icon' => 'fa fa-pencil-square-o',
                        'name' => 'Reports settings',
                        'route' => $router->url('reports:admin_reports'),
                    ],
                ] : false,
            ],
        ];
    }
}