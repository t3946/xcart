<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Sites\Forms\SiteForm;
use Modules\Sites\Models\SiteModel;

class SitesAdmin extends Admin
{
    public ?string $sort = 'orderby';

    use AdminTrait;

    public function getListColumns()
    {
        return [
            'code',
            'domain',
            'status',
        ];
    }

    public function getForm()
    {
        return new SiteForm();
    }

    public function getModel()
    {
        return new SiteModel();
    }

    public static function getName()
    {
        return 'Sites';
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
