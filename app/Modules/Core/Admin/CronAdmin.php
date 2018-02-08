<?php
namespace Modules\Core\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Core\Forms\CronForm;
use Modules\Core\Forms\StaticNotificationForm;
use Modules\Core\Models\StaticNotificationModel;

class CronAdmin extends Admin
{
    public static function getName()
    {
        return "Cron commands";
    }

    public function getForm()
    {
        return new CronForm();
    }
}