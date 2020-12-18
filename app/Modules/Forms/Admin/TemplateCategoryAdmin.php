<?php


namespace Modules\Forms\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Forms\Forms\TemplateCategoryForm;
use Modules\Forms\Forms\TemplateFilterForm;
use Modules\Forms\Forms\TemplateForm;
use Modules\Forms\Models\TemplateCategoryModel;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class TemplateCategoryAdmin extends Admin
{

    public static $public = false;

    public ?string $sort = 'pos';

    public function getListColumns()
    {
        return [
            'name',
        ];
    }

    public function getForm()
    {
        return new TemplateCategoryForm;
    }

    public function getModel()
    {
        return new TemplateCategoryModel;
    }

    public static function getName()
    {
        return 'Template categories';
    }

    public function getBreadcrumbs()
    {
        return array_merge([['General settings', '/admin/configuration.php']], parent::getBreadcrumbs());
    }

    /*public function getListItemActions()
    {
        return [
            'update',
        ];
    }*/
}