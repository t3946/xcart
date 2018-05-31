<?php

namespace Modules\Main;

use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Main\Models\EmployeesModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class MainModule extends Module
{

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorCallback('getTeam', function () {

            return EmployeesModel::objects()->order(['-isCeo', 'position', 'id'])->all();
        });

        $template->addAccessorCallback('workingDayTimeNow', function () {
           return WorkingTimeHelper::workingDayTimeNow();
        });

    }

}