<?php

namespace Modules\Goods\Admin;


use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Models\CouponKitModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Forms\ProductAdminForm;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class ProductAdmin extends Admin
{

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
        return [
            'forsale',
            'image',
            'productcode',
            'product',
            'add_date'
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

    public static function getName()
    {
        return 'Products';
    }

    public function getListItemActions()
    {
        return [
            'update',
            'view',
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        /** @var ProductModel $image */
        if ($property === 'image' && $image = $item->getMainImage()) {
            return "<div style='text-align: center'><img src=\"/{$image->getCdnURL(60)}\" title=\"{$item}\" width='60' /></div>";
        }
        if ($property === 'forsale') {
            return $item->forsale === 'Y' ? 'Active' : 'Inactive';
        }
        if ($property === 'add_date') {
            return (new DateTime())->setTimestamp($item->add_date)->format('d-M-Y H:i:s');
        }

        return parent::getItemProperty($item, $property);
    }

    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                $this->sort
            ]);
        } else {
            $qs->order([
                '-add_date'
            ]);
        }
        return $qs;
    }
}