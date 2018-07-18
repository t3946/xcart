<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductOptionVariantsAdminForm;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\ModelForm;

class ProductOptionVariantsAdmin extends ListViewAdmin
{
    public $ownerField = 'product_option';

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
}