<?php
namespace Modules\Core\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Core\Forms\CronForm;
use Modules\Core\Forms\StaticNotificationForm;
use Modules\Core\Models\StaticNotificationModel;

class CronAdmin extends Admin
{
    public $listRowTemplate = 'admin/cron/tr.tpl';

    public static function getName()
    {
        return "Cron commands";
    }

    public function getForm()
    {
        return new CronForm();
    }

    public function getAvailableListColumns()
    {
        return array_merge(parent::getAvailableListColumns() ,[
            'name',
            'active',
            'is_run',
            'run_force',
            'run_start' => [
                'title' => 'Run start',
                'template' => 'admin/cron/column_run_start.tpl',
            ],
            'run_end' => [
                'title' => 'Run end',
                'template' => 'admin/cron/column_run_end.tpl',
            ],
            'run_next' => [
                'title' => 'Next running',
                'template' => 'admin/cron/column_run_next.tpl',
            ],

        ]);
    }
}