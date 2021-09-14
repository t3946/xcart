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

    public $listItemActionsTemplate = 'admin/list/custom_item_actions.tpl';

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
            'image',
            'productcode',
            'product',
            'add_date',
            'forsale',
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
            'image'
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        /** @var ProductModel $image */
        if ($property === 'image') {
            return ($image = $item->getMainImage())
                ? "<div style='text-align: center'><img src=\"/{$image->getCdnURL(174)}\" title=\"{$item}\" width='60' /></div>"
                : '';
        }
        if ($property === 'product') {
            return "<a target='_blank' href='{$item->getAbsoluteUrl()}'>{$item->getFrontendName()}</a>";
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