<?php

namespace Modules\Goods\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ProductAdminForm extends ModelForm
{
    public $exclude = [
        'categories',
        'product_categories',
        'prices',
        'sites',
        'quick_prices',
        'clean_url',
        'order_details',
        'surf_path',
        'sf_moves',
        'distributor',
        'brand',
        'filter_values',
        'images',
        'videos',
    ];

    public function getFieldsets()
    {
        return [
            'Product details' => [
                'product',
                'fulldescr',
            ],
            'Product options' => [
                'product_options'
            ],

        ];
    }

    public function getFields()
    {
        return [
            'product' => [
                'class' => CharField::class,
                'required' => true,
            ],
            'fulldescr' => EditorField::class,
            'product_options' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionsAdmin::class,
                'defaultOrder' => [
                    'class'
                ]
            ],
        ];
    }

    public function getModel()
    {
        return new ProductModel();
    }
}