<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class ShippingCacheLocationModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_shipping_cache_location';
    }

    public static function getFields()
    {
        return [
            'shipping_location_id' => [
                'class' => AutoField::class
            ],
            'quotes' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingCacheQuoteModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingCacheProductModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
            ],

        ];
    }
}