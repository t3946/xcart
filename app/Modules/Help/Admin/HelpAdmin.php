<?php


namespace Modules\Help\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Help\Forms\HelpForm;
use Modules\Help\Models\HelpListModel;

class HelpAdmin extends Admin
{
    public ?string $sort = 'order_by';

    public function getListColumns() : array
    {
        return [
            'title',
        ];
    }

    public function getForm() : HelpForm
    {
        return new HelpForm();
    }

    public function getModel()
    {
        return new HelpListModel();
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }
    public static function getName()
    {
        return 'Help center';
    }
}