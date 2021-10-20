<?php
namespace Modules\Distributor;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class DistributorModule extends Module
{
    use AdminTrait;

    public static function getAdminMenu()
    {
        $menu = [];
        $adminClasses = static::getAdminClasses();
        foreach ($adminClasses as $adminClass) {
            if (is_a($adminClass, Admin::class, true) && $adminClass::$public) {
                    $menu[] = [
                        'adminClassName' => $adminClass::className(),
                        'adminClassNameShort' => $adminClass::classNameShort(),
                        'moduleName' => static::getName(),
                        'name' => $adminClass::getName(),
                        'route' => Xcart::app()->router->url('admin:list', [
                            'module' => static::getName(),
                            'admin' => $adminClass::classNameShort()
                        ])
                    ];
            }
        }
        return $menu;
    }
}