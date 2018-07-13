<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Form\ModelForm;

class ProductOptionVariantsAdmin extends ListViewAdmin
{
    public function getModel()
    {
        return new ProductOptionVariantModel();
    }

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        // TODO: Implement getForm() method.
    }
}