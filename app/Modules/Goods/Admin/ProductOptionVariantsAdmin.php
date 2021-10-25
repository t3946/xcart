<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductOptionVariantsAdminForm;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\QuerySet;

class ProductOptionVariantsAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'product_option_id';
    public ?string $sort = 'position';

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
    
    public function getForm() : ProductOptionVariantsAdminForm
    {
        return new ProductOptionVariantsAdminForm();
    }

    public static function getItemName()
    {
        return 'Variant';
    }

    public function getListColumns() : array
    {
        return ['(string)'];
    }

    public function getAvailableListColumns()
    {
        return [
            '(string)' => [
                'title' => 'VARIANT',
                'template' => $this->columnDefaultTemplate,
                'order' => 'position'
            ]
        ];
    }

    public function getAllUrl()
    {
        if ($this->ownerPk->id) {
            return (new ProductOptionsAdmin)->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return (new ProductOptionsAdmin)->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}