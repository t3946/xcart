<?php

namespace Modules\PBX;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class PBXModule extends Module
{
    public static function getAdminMenu()
    {
        $router = Xcart::app()->router;

        return [
            [
                'name' => 'View calls',
                'route' => $router->url('admin_pbx:view'),
            ]
        ];
    }


}