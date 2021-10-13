<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ZoneElementModel extends Model
{
    public static function tableName()
    {
        return 'xcart_zone_element';
    }

    public static function getFields()
    {
        return [
            'field' => [
                'class' => CharField::class,
                'primary' => true,
            ],
            'field_type' => [
                'class' => CharField::class,
                'primary' => true,
            ],
            'zone' => [
                'field' => 'zoneid',
                'class' => ForeignField::class,
                'modelClass' => ZoneModel::class,
                'link' => ['zoneid' => 'zoneid'],
                'primary' => true,
            ],
            'shipping_rates' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingRateModel::class,
                'link' => ['zoneid' => 'zoneid'],
            ]
        ];
    }
}