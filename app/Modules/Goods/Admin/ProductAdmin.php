<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Cart\Models\CouponKitModel;
use Modules\Goods\Forms\ProductAdminForm;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\ModelForm;

class ProductAdmin extends Admin
{

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new ProductAdminForm();
    }

    public function getModel()
    {
        return new ProductModel();
    }

    public function getListColumns()
    {
        return ['productid','productcode','(string)'];
    }

    public function getAvailableListColumns()
    {
        return [
            'productid' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'productid'
            ],
            'productcode' => [
                'title' => 'SKU',
                'template' => $this->columnDefaultTemplate,
                'order' => 'productcode'
            ],
            '(string)' => [
                'title' => 'PRODUCT',
                'template' => $this->columnDefaultTemplate,
                'order' => 'product'
            ],
        ];
    }
}