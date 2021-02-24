<?php


namespace Modules\Forms\Forms;


use Modules\Forms\Models\TemplateCategoryModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Form;

class TemplateFilterForm extends Form
{
    public function getFields()
    {
        return [
            'category' => [
                'class' => DropDownField::class,
                'empty' => 'All',
                'inputTemplate' => 'forms/field/dropdown/input_nested.tpl',
                'choices' => function () {
                    foreach (TemplateCategoryModel::objects()->order(['root', 'level', 'pos']) as $category) {
                        $level = $category['level'] ? $category['level'] - 1 : $category['level'];
                        $list[$category['id']] = $level ? str_repeat("..", $level) . ' ' . $category['name'] : $category['name'];
                    }
                    $list[''] = 'Not assigned';
                    return $list ?? [];
                },
                'label' => 'Template category'
            ],
            'active' => [
                'class' => DropDownField::class,
                'choices' => [
                    '' => 'All',
                    'Y' => 'Yes',
                    'N' => 'No',
                ],
                'label' => 'Active?'
            ]
        ];
    }
}