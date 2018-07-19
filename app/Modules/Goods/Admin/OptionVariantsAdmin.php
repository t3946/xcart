<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Models\OptionVariantModel;
use Xcart\App\Form\ModelForm;

class OptionVariantsAdmin extends ListViewAdmin
{
    public $ownerField = 'option';

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        // TODO: Implement getForm() method.
    }

    public function getModel()
    {
        return new OptionVariantModel();
    }
}