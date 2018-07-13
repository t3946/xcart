<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductOptionsAdminForm;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class ProductOptionsAdmin extends ListViewAdmin
{
    public $ownerField = 'product';

    public function getExcludedColumns()
    {
        return ['product', 'variants'];
    }

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new ProductOptionsAdminForm();
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }

    public function getListColumns()
    {
        return ['(string)', 'var'];
    }

    public static function getItemName()
    {
        return 'Option';
    }

    public function getAvailableListColumns()
    {
        return array_merge(parent::getAvailableListColumns(),[
            'var' => [
                'title' => 'VARIANTS',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ]
        ]);
    }

    public function getItemProperty(Model $item, $property)
    {
        return nl2br(implode(";\n", $item->variants->all()));
    }

}