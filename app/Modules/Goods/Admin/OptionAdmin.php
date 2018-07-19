<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Goods\Forms\OptionAdminForm;
use Modules\Goods\Models\OptionNewModel;
use Xcart\App\Form\ModelForm;

class OptionAdmin extends Admin
{
    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new OptionAdminForm();
    }

    public function getModel()
    {
        return new OptionNewModel();
    }
}