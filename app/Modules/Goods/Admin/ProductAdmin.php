<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Models\CouponKitModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Forms\ProductAdminForm;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\ModelForm;

class ProductAdmin extends Admin
{

    public function getSearchColumns()
    {
        return ['productcode'];
    }

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new ProductAdminForm();
    }

    public function getModel()
    {
        return new ProductModel();
    }

    public function getListColumns()
    {
        return ['productid','productcode','(string)', 'forsale'];
    }

    public function getAvailableListColumns()
    {
        return [
            'productid' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'productid'
            ],
            'productcode' => [
                'title' => 'SKU',
                'template' => $this->columnDefaultTemplate,
                'order' => 'productcode'
            ],
            '(string)' => [
                'title' => 'PRODUCT',
                'template' => $this->columnDefaultTemplate,
                'order' => 'product'
            ],
            'forsale' => [
                'title' => 'FORSALE',
                'template' => $this->columnDefaultTemplate,
                'order' => 'forsale'
            ],
        ];
    }

    public function getSuggestionColumns()
    {
        return [
            'brand' => [
                'class' => BrandModel::class,
                'columns' => [
                    'brand', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y', 'parent__isnull' => true,
                ]
            ],
            'category' => [
                'class' => CategoryModel::class,
                'columns' => [
                    'category', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y'
                ]
            ],
            'distributor' => [
                'class' => DistributorModel::class,
                'columns' => [
                    'manufacturer', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y'
                ]
            ],
        ];
    }

    public function getBottomLinks()
    {
        return [
            [
                'url' => $this->getIn
            ]
        ];
    }

    public static function getName()
    {
        return 'Products';
    }
}