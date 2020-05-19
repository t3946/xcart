<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\ModelForm;

class DistributorForm extends ModelForm
{
    public $templates = [
        'default' => 'admin/distributor/form/_dx_form.tpl'
    ];
    public $fieldTemplate = 'admin/distributor/form/field.tpl';
    public $hintTemplate = 'admin/distributor/form/hint.tpl';
}