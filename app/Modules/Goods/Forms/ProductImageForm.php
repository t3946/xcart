<?php

namespace Modules\Goods\Forms;

use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductImagesModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;
use Xcart\App\Storage\Files\RemoteFile;
use Xcart\App\Storage\Files\ResourceFile;

class ProductImageForm extends ModelForm
{
    public array $exclude = [
        'products',
        'hash',
        'link',
        'width',
        'height',
        'link_uri',
        'is_downloaded',
        'is_manual',
    ];

    public function getFields()
    {
        return [
            'path' => [
                'class' => ImageField::class,
                'label' => 'Image'
            ],
            'products_images__is_active' => [
                'class' => Select2Field::class,
                'inline_editor' => true,
                'choices' => [
                    0 => 'Disable',
                    1 => 'Active',
                ],
                'html' => [
                    'style' => 'width: 150px',
                ],
            ]
        ];
    }

    public function afterInstanceSave($instance)
    {
        $need = $this->checkNeedExecuteAfterOrBefore();
        if (!$need) {
            return;
        }
        $field = $instance->getField('path');
        [$instance->width, $instance->height] = $field->getImageSizes();
        $instance->hash = md5(file_get_contents($_FILES[self::classNameShort()]['tmp_name']['path']));
        $instance->is_manual = true;
        $instance->save();
    }
    public function beforeInstanceSave($instance)
    {
        $need = $this->checkNeedExecuteAfterOrBefore();
        if (!$need) {
            $this->beforeInstanceInlineEditorSave($instance);
        }
    }
    public function beforeInstanceInlineEditorSave($instance)
    {
        $form_data = $_POST[self::classNameShort()];
        foreach ($form_data as $property => $value) {
            if (strpos($property, '__') !== false) {
                $ar_property = explode('__', $property);
                $parent_model_list = $instance->{$ar_property[0]}->get(['product_id' => $_POST['ownerPk']]);
                if ($parent_model_list) {
                    $parent_model_list->{$ar_property[1]} = $value;
                    $parent_model_list->save();
                }
            }
        }
    }

    public function getFieldsets()
    {
        return [[
            'path',
        ]];
    }

    public function getModel()
    {
        return new ProductImageModel();
    }
}