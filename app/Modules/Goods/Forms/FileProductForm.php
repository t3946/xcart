<?php

namespace Modules\Goods\Forms;

use Modules\Goods\Admin\FilesProductAdmin;
use Modules\Goods\Models\ProductFileModel;
use Xcart\App\Form\Fields\FileField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

class FileProductForm extends ModelForm
{
    public array $exclude = [
        'filesize',
        'date',
    ];
    public function getModel()
    {
        return new ProductFileModel();
    }

    public function getFields()
    {
        return [
            'filename' => [
                'class' => FileField::class,
                'label' => 'File'
            ],
            'description' => [
                'class' => TextAreaField::class,
            ],
            'avail' => [
                'class' => Select2Field::class,
                'inline_editor' => true,
                'choices' => [
                    'N' => 'No',
                    'Y' => 'Yes',
                ],
                'html' => [
                    'style' => 'width: 70px'
                ]
            ]
        ];
    }
    public function getFieldsets()
    {
        return [[
            'filename',
            'description'
        ]];
    }

    /**
     * @param ProductFileModel $instance
     */
    public function beforeInstanceSave($instance)
    {
        if ($instance->getIsNewRecord()) {
            $instance->filesize = $_FILES[self::classNameShort()]['size']['filename'];
            $instance->date = time();
        }
    }
}