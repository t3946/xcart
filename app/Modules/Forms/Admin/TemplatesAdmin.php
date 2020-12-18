<?php


namespace Modules\Forms\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Forms\Forms\TemplateFilterForm;
use Modules\Forms\Forms\TemplateForm;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class TemplatesAdmin extends Admin
{
    use AdminTrait;

    public ?string $sort = 'pos';

    public static $public = false;

    public $createTemplate = 'template/_create.tpl';
    public $updateTemplate = 'template/_update.tpl';

    public function getListColumns()
    {
        return [
            'template_name',
            'subject_line',
            'category',
            'active'
        ];
    }

    public function getBreadcrumbs()
    {
        return array_merge([['General settings', '/admin/configuration.php']], parent::getBreadcrumbs());
    }

    public function getForm()
    {
        return new TemplateForm;
    }

    public function getModel()
    {
        return new TemplateModel;
    }

    public static function getName()
    {
        return 'Templates for order-related messages';
    }

    public function getFilterForm()
    {
        return new TemplateFilterForm;
    }

    public function handleFilter(QuerySet $qs, $form): QuerySet
    {
        $qs = parent::handleFilter($qs, $form);
        if ($form->department->getValue() === null) {
            $qs->filter(['department__isnull' => true]);
        }
        return $qs;
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'department') {
            return ($field = $item->getField($property)) ? $field->toText() : '';
        }
        return parent::getItemProperty($item, $property);
    }

    public function getListItemActions()
    {
        return [
            'update',
        ];
    }
}