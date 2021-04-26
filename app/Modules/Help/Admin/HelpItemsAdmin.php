<?php


namespace Modules\Help\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Help\Forms\HelpItemsForm;
use Modules\Help\Models\HelpMenuContentModel;

class HelpItemsAdmin extends ListViewAdmin
{
    public ?string $sort = 'order_by';
    public $ownerField = 'menu_items';

    public function getListColumns()
    {
        return [
            'form_type',
            'answer',
            'question',
        ];
    }

    public function getForm()
    {
        return new HelpItemsForm();
    }

    public function getModel()
    {
        return new HelpMenuContentModel();
    }

    public function isAjaxUpdate(): bool
    {
        return false;
    }

    public function isAjaxCreate(): bool
    {
        return false;
    }
}