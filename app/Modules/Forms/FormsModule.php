<?php
namespace Modules\Forms;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class FormsModule extends Module
{
    use AdminTrait;

    public static function getAdminMenu()
    {
        $menu = [];
        $menu[] =  [
            'name' => 'Inbox/Sorting dashboard',
            'route' => Xcart::app()->router->url('forms:page', ['page' => 1])
        ];
        $adminClasses = static::getAdminClasses();
        foreach ($adminClasses as $adminClass) {
            if (is_a($adminClass, Admin::className(), true) && $adminClass::$public) {
                if (!Xcart::app()->user->hasRoles(['vrs','vrv'])) {
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
        }

        return $menu;
    }

    public static function getVerboseName()
    {
        return 'Emails';
    }

}