<?php

namespace Modules\Goods\Admin;

use Modules\Admin\Contrib\NestedAdmin;
use Modules\Goods\Forms\CategoryForm;
use Modules\Goods\Forms\FilterForms\CategoryFilterForm;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;
use Xcart\App\QueryBuilder\Q\QOr;

class CategoryAdmin extends NestedAdmin
{
    public ?string $sort = 'order_by';
    public bool $autoFixSort = false;
    public ?string $order = 'order_by';

    public function getListColumns(): array
    {
        return [
            'category',
            'products',
            'pc_ready_to_classify',
            'prevent_index_products',
            'avail',
            'is_bold'
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'products':
                return "<a target='_blank' href='{$item->getAbsoluteUrl()}'>$item->product_count ($item->global_product_count)</a>";
        }
        return parent::getItemProperty($item, $property);
    }

    public function getFilterForm(): ?Form
    {
        return new CategoryFilterForm();
    }

    public function getForm(): ?ModelForm
    {
        return new CategoryForm();
    }

    public function getModel(): CategoryModel
    {
        return new CategoryModel();
    }
    public static function getName()
    {
        return 'Categories';
    }
    public function getSelectName(): string
    {
        return $this->getInstance()->pk
            ? (string)$this->getInstance()
            : static::getName();
    }


    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function getListItemActions(): array
    {
        return [
            'update',
        ];
    }
}