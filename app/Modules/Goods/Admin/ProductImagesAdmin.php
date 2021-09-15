<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Forms\ProductImageForm;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductImagesModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class ProductImagesAdmin extends ListViewAdmin
{
    public $ownerField = 'image_id';
    public function getSuggestionColumns()
    {
        return  [
            'is_active' => [
                'class' => CharField::class
            ],
        ];
    }

    public function getOwnerModel() : ProductImagesModel
    {
        return new ProductImagesModel();
    }

    public function getListColumns()
    {
        return [
            'image',
            'hash',
            'width',
            'height',
        ];
    }

    public function getModel()
    {
        return new ProductImageModel();
    }

    public function getForm()
    {
        return new ProductImageForm();
    }
    public function getItemProperty(Model $item, $property)
    {
        switch ($property)
        {
            case 'image':
                return "<div style='text-align: center'><img src=\"{$item->getCdnURL(174)}\" title=\"{$item}\" width='60' /></div>";
        }
        return parent::getItemProperty($item, $property);
    }
}