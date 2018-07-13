<?php

namespace Modules\Goods\Forms;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\ModelForm;

class ProductOptionsAdmin extends ListViewAdmin
{

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        // TODO: Implement getForm() method.
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }
}