<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\ShareHolderAdmin;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\NumberField;

class ShareholdersForm extends CorporatesForm
{
    public function getFieldsets()
    {
        return [[
            'shares',
            'shareholders',
        ]];
    }

    public function getFields()
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

    public function getName()
    {
        return 'Shareholders';
    }
}