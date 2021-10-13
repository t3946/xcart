<?php

namespace Modules\Shipping\Models;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ShippingProductModel extends Model
{
    public static function tableName()
    {
        return 'xcart_shipping_product';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'primary' => true,
            ],
            'shipping_rate' => [
                'field' => 'shipping_rate_id',
                'class' => ForeignField::class,
                'modelClass' => ShippingRateModel::class,
                'link' => ['shipping_rate_id' => 'rateid'],
                'primary' => true,
            ],
            'weight_ratio' => [
                'class' => FloatField::class,
            ],
        ];
    }
}