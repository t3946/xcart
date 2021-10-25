<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\NestedAdmin;
use Modules\Sites\Forms\MenuForm;
use Modules\Sites\Models\SiteMenuModel;

class MenuAdmin extends NestedAdmin
{
    public ?string $sort = 'pos';

    public function getListColumns(): array
    {
        return [
            'name',
        ];
    }

    public function getForm(): MenuForm
    {
        return new MenuForm();
    }

    public function getModel(): SiteMenuModel
    {
        return new SiteMenuModel();
    }

    public static function getName(): string
    {
        return 'Site menu';
    }

    public function getListItemActions(): array
    {
        return [
            'update',
        ];
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