<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ShippingCacheQuoteModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_shipping_cache_quotes';
    }

    public static function getFields()
    {
        return [
            'shipping_cache' => [
                'field' => 'shipping_cache_id',
                'class' => ForeignField::class,
                'modelClass' => ShippingCacheLocationModel::class,
                'link' => ['shipping_cache_id' => 'shipping_cache_id'],
                'primary' => true,
            ],
            'shipping_rate' => [
                'field' => 'rate_id',
                'class' => ForeignField::class,
                'modelClass' => ShippingRateModel::class,
                'link' => ['rate_id' => 'rateid'],
                'primary' => true,
            ],

        ];
    }
}