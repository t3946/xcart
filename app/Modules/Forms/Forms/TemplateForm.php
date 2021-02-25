<?php


namespace Modules\Forms\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Forms\Admin\TemplateCategoryAdmin;
use Modules\Forms\Models\TemplateCategoryModel;
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
        return new TemplateModel();
    }

    public function getFields()
    {
        return [
            'message_body' => [
                'class' => EditorField::class,
            ],
            'category' => [
                'class' => DropDownField::class,
                'inputTemplate' => 'forms/field/dropdown/input_nested.tpl',
                'required' => true,
                'extend' => 'category_link',
                'choices' => function () {
                    foreach (TemplateCategoryModel::objects()->order(['root', 'level', 'pos']) as $category) {
                        $level = $category['level'] ? $category['level'] - 1 : $category['level'];
                        $list[$category['id']] = $level ? str_repeat("..", $level) . ' ' . $category['name'] : $category['name'];
                    }
                    return $list ?? [];
                },
            ],
            'category_link' => [
                'hidden' => true,
                'class' => LinkField::class,
                'link_content' => 'Edit template categories',
                'html' => ['class' => 'admin_link', 'target' => '_blank'],
                'value' => (new TemplateCategoryAdmin())->getAllUrl(),
            ],
            'status' => [
                'class' => DropDownField::class,
            ],
        ];
    }

    public function getName()
    {
        return '';
    }
}