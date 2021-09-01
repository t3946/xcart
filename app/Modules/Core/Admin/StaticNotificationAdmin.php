<?php
namespace Modules\Core\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Core\Forms\StaticNotificationForm;
use Modules\Core\Models\StaticNotificationModel;

class StaticNotificationAdmin extends Admin
{
    public static function getName()
    {
        return "Static notifications";
    }

    public function getAvailableListColumns()
    {
        return array_replace_recursive(parent::getAvailableListColumns(), [
            'active' => [
                'template' => 'admin/list/columns/boolean.tpl',
            ]
        ]);
    }

    public function getListColumns()
    {
        return ['title', 'active'];
    }


    public function getForm()
    {
        return new StaticNotificationForm();
    }
}