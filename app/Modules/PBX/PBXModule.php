<?php

namespace Modules\PBX;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class PBXModule extends Module
{
    public static function getAdminMenu()
    {
        if (Xcart::app()->user->hasRoles(['vrs','vrv'])) {
            return [];
        }

        $router = Xcart::app()->router;

        return [
            [
                'name' => 'Call recordings',
                'route' => $router->url('admin_pbx:view'),
            ]
        ];
    }


}