<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\ShareHolderAdmin;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\NumberField;

class ShareholdersForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets() : array
    {
        return [[
            'shares',
            'shareholders',
        ]];
    }

    public function getFields() : array
    {
        return [
            'shares' => [
                'class' => NumberField::class,
                'html' => ['style' => 'width:100px;'],
            ],
            'shareholders' => [
                'class' => ListViewField::class,
                'adminClass' => ShareHolderAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Shareholders'
            ]
        ];
    }

    public function getName() : string
    {
        return 'Shareholders';
    }
}