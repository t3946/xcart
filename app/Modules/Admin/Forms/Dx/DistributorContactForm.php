<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorContactsModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\RadioField;

class DistributorContactForm extends DistributorForm
{

    public function getFieldsets()
    {
        return [[
            'pq',
            'contact_name',
            'distributor_field_name',
            'email',
            'phone',
            'ext',
            'fax',
        ]];
    }

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getDx()
    {
        return $this->getInstance()->distributor;
    }

    public function getFields()
    {
        return [
            'pq' => [
                'class' => RadioField::class,
                'label' => 'Pq',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:1rem;'],
                'template' => "<input type='{type}' id='{id}' value='{$this->getInstance()->id}' name='{name}'{html}/>",
            ],
            'contact_name' => [
                'class' => CharField::class,
                'label' => 'Contact name',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
            'distributor_field_name' => [
                'class' => CharField::class,
                'label' => 'Position',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
            'email' => [
                'class' => CharField::class,
                'label' => 'Email',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
            'phone' => [
                'class' => CharField::class,
                'label' => 'Phone',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
            'ext' => [
                'class' => CharField::class,
                'label' => 'Ext',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
            'fax' => [
                'class' => CharField::class,
                'label' => 'Fax',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input_mult.tpl',
            ],
        ];
    }
}