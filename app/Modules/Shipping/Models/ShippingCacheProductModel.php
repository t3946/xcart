<?php

namespace Modules\Shipping\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ShippingCacheProductModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_shipping_cache_products';
    }

    public static function getFields()
    {
        return [
            'shipping_cache' => [
                'field' => 'shipping_cache_id',
                'class' => ForeignField::class,
                'model' => ShippingCacheModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
                'primary' => true,
            ],

        ];
    }

}