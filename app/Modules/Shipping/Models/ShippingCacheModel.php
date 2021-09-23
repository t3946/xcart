<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class ShippingCacheModel extends Model
{

    public static function tableName()
    {
        return 'xcart_shipping_cache';
    }

    public static function getFields()
    {
        return [
            'shipping_cache_id' => [
                'class' => AutoField::class
            ],
            'cache_date' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'null' => false
            ],
            'shipping_carrier' => [
                'class' => CharField::class,
                'null' => false
            ],
            'shipping_location' => [
                'field' => 'shipping_location_id',
                'class' => ForeignField::class,
                'modelClass' => ShippingCacheLocationModel::class,
                'link' => ['shipping_location_id' => 'shipping_location_id'],
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingCacheProductModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
            ],
            'quotes' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingCacheQuoteModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
            ]
        ];
    }
}