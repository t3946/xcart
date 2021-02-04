<?php


namespace Modules\Forms\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Forms\Forms\TemplateFilterForm;
use Modules\Forms\Forms\TemplateForm;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Form\Form;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class TemplatesAdmin extends Admin
{
    use AdminTrait;

    public ?string $sort = 'pos';

    public static $public = false;

    public $allTemplate = 'template/all.tpl';
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

    public function getBreadcrumbs(): array
    {
        return array_merge([['General settings', '/admin/configuration.php']], parent::getBreadcrumbs());
    }

    public function getForm()
    {
        return new TemplateForm();
    }

    public function getModel()
    {
        return new TemplateModel();
    }

    public static function getName()
    {
        return 'Templates for order-related messages';
    }

    public function getFilterForm(): Form
    {
        return new TemplateFilterForm();
    }

    public function handleFilter(QuerySet $qs, $form): QuerySet
    {
        if ($form->category->getValue() === $form->category->empty) {
            $form->category->setValue(null);
        }
        $qs = parent::handleFilter($qs, $form);

        if ($form->category->getValue() === '') {
            $qs->filter(['category_id__isnull' => true]);
        }
        return $qs;
    }

    public function getItemProperty(Model $item, $property)
    {
        if (($property === 'category') && $category = $item->category) {
            return implode(" > ", $category->getObjects()->ancestors(true)->order(['lft'])->all());
        }
        return parent::getItemProperty($item, $property);
    }

    public function getListItemActions()
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

    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                'category__pos',
                $this->sort
            ]);
        }
        return $qs;
    }
}