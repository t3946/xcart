<?php
namespace Modules\Product;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class ProductModule extends Module
{
    public static function getAdminMenu()
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        return [
            [
                'icon' => 'fa fa-list',
                'name' => 'Group products',
                'route' => $router->url('product:group_products'),
                'items' => ($user && $user->getIsSuperuser()) ? [
                    [
                        'icon' => 'fa fa-object-group',
                        'name' => 'Grouping products',
                        'route' => $router->url('product:group_list'),
                    ],
                ] : false,
            ],
        ];
    }
}