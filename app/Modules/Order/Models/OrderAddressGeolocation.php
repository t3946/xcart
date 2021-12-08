<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property OrderModel order
 * @property int order_id
 * @property int geolocation_id
 * @property OrderAddressType address_type
 * @property int address_type_id
 * @property float longitude
 * @property float latitude
 */
class OrderAddressGeolocation extends Model
{
    public static function tableName(): string
    {
        return 'xcart_order_address_geolocation';
    }

    public static function getFields(): array
    {
        return [
            'geolocation_id' => AutoField::class,
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
            ],
            'address_type' => [
                'field' => 'address_type_id',
                'class' => ForeignField::class,
                'modelClass' => OrderAddressType::class,
                'link' => ['address_type_id' => 'address_type_id'],
            ],
            'longitude' => [
                'class' => FloatField::class,
                'default' => null,
                'null' => true,
            ],
            'latitude' => [
                'class' => FloatField::class,
                'default' => null,
                'null' => true,
            ]
        ];
    }
}