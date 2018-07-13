<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\ProductOptionModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductOptionVariantsAdminForm extends ModelForm
{
    public function getFields()
    {
        return [

        ];
    }

    public function getModel()
    {
        return new ProductOptionVariantModel();
    }
}