<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Form;

class DistributorForm extends Form
{
    public $templates = [
        'default' => 'admin/distributor/form/_dx_form.tpl'
    ];
    public $fieldTemplate = 'admin/distributor/form/field.tpl';
    public $hintTemplate = 'admin/distributor/form/hint.tpl';
}