<?php


namespace Modules\Forms\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Forms\Admin\TemplateCategoryAdmin;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\LinkField;
use Xcart\App\Form\ModelForm;

class TemplateForm extends ModelForm
{
    public array $exclude = [
        'pos',
        'ca_status',
    ];

    public function getModel()
    {
        return new TemplateModel;
    }

    public function getFields()
    {
        return [
            'message_body' => [
                'class' => EditorField::class,
                'required' => true,
            ],
            'department' => [
                'class' => DropDownField::class,
                'choices' => [
                    'customer' => 'Customer',
                    'distributor' => 'Distributor',
                    'our_customer_service' => 'Our customer service',
                    'third_party' => 'Third party',
                ],
            ],
            'category' => [
                'class' => DropDownField::class,
                'required' => true,
                'extend' => 'category_link',
            ],
            'category_link' => [
                'hidden' => true,
                'class' => LinkField::class,
                'link_content' => 'Edit categories',
                'html' => ['class' => 'admin_link', 'target' => '_blank'],
                'value' => (new TemplateCategoryAdmin)->getAllUrl(),
            ],
            'status' => [
                'class' => DropDownField::class,
            ],
        ];
    }

    public function getName()
    {
        return ' Templates for order-related messages';
    }
}