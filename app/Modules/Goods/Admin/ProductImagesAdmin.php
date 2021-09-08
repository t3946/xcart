<?php

namespace Modules\Goods\Forms;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Admin\ProductOptionVariantsAdmin;
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

class ProductImagesAdmin extends ListViewAdmin
{
    public $ownerField = 'product_id';
    public function getFields()
    {
        return  [

            'path' => [
                'class' => ImageField::class,
            ],
/*            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionVariantsAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'defaultOrder' => 'position'
            ],*/
        ];
    }

    public function getModel()
    {
        return new ProductImagesModel();
    }

    public function getForm()
    {
        return ;
        // TODO: Implement getForm() method.
    }
}