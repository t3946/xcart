<?php


namespace Modules\Help\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Help\Forms\HelpItemsForm;
use Modules\Help\Models\HelpMenuContentModel;

class HelpItemsAdmin extends ListViewAdmin
{
    public ?string $sort = 'order_by';
    public ?string $ownerField = 'menu';
    public static bool $public = false;

    public function getListColumns(): array
    {
        return [
            'form_type',
            'answer',
            'question',
        ];
    }

    public function getForm(): HelpItemsForm
    {
        return new HelpItemsForm();
    }

    public function getModel()
    {
        return new HelpMenuContentModel();
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }
}