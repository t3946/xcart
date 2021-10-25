<?php
namespace Modules\Core\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Core\Forms\CronForm;

class CronAdmin extends Admin
{
    public string $listRowTemplate = 'admin/cron/tr.tpl';

    public function getListColumns(): array
    {
        return ['name', 'active', 'is_run', 'run_force', 'run_start', 'run_end', 'run_next'];
    }

    public static function getName()
    {
        return "Cron commands";
    }

    public function getForm(): CronForm
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