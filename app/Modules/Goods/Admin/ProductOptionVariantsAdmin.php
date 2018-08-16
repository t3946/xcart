<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductOptionVariantsAdminForm;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\ModelForm;

class ProductOptionVariantsAdmin extends ListViewAdmin
{
    public $ownerField = 'product_option_id';
    public $sort = 'position';

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new ProductOptionVariantsAdminForm();
    }

    public static function getItemName()
    {
        return 'Variant';
    }

    public function getListColumns()
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

    public function getCanSort($qs)
    {
        return true;
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

    public function getBreadcrumbs()
    {
        return [];
    }
}