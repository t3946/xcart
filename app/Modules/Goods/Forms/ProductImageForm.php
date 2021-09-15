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
use Xcart\App\Form\ModelForm;

class ProductImageForm extends ModelForm
{
    public array $exclude = [
        'products',
        'hash',
        'link',
        'link_uri',
        'is_downloaded',
        'is_manual'
    ];
    public function getFields()
    {
        return  [
            'path' => [
                'class' => ImageField::class,
            ],
            'width' => [
                'class' => CharField::class
            ],
            'height' => [
                'class' => CharField::class,
            ],
        ];
    }
    public function getFieldsets()
    {
        return [[
            'path',
            'width',
            'height',
        ]];
    }

    public function getModel()
    {
        return new ProductImageModel();
    }
}