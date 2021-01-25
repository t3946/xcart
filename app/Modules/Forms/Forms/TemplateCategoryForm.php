<?php


namespace Modules\Forms\Forms;


use Modules\Forms\Models\TemplateCategoryModel;
use Xcart\App\Form\ModelForm;

class TemplateCategoryForm extends ModelForm
{
    public array $exclude = ['pos'];

    public function getModel()
    {
        return new TemplateCategoryModel;
    }

    public function getName()
    {
        return 'Template Category';
    }
}