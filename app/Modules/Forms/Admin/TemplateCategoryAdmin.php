<?php


namespace Modules\Forms\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\NestedAdmin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Forms\Forms\TemplateCategoryForm;
use Modules\Forms\Forms\TemplateFilterForm;
use Modules\Forms\Forms\TemplateForm;
use Modules\Forms\Models\TemplateCategoryModel;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class TemplateCategoryAdmin extends NestedAdmin
{

    public static bool $public = false;

    public ?string $sort = 'pos';

    public function getListColumns(): array
    {
        return [
            'name',
        ];
    }

    public function getForm(): TemplateCategoryForm
    {
        return new TemplateCategoryForm();
    }

    public function getModel(): TemplateCategoryModel
    {
        return new TemplateCategoryModel();
    }

    public static function getName(): string
    {
        return 'Template categories';
    }

    public function getBreadcrumbs(): array
    {
        return array_merge([['General settings', '/admin/configuration.php']], parent::getBreadcrumbs());
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