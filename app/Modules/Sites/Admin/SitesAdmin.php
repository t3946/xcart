<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Forms\Forms\TemplateForm;
use Modules\Forms\Models\TemplateModel;
use Modules\Sites\Forms\SiteForm;
use Modules\Sites\Models\SiteModel;

class SitesAdmin extends Admin
{
    use AdminTrait;

//    public static $public = false;


    public function getListColumns()
    {
        return [
            'code',
            'domain',
            'prefix'
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
        return 'Sites here';
    }

//    public function isAjaxUpdate(): bool
//    {
//        return true;
//    }
//
//    public function isAjaxCreate(): bool
//    {
//        return true;
//    }
}