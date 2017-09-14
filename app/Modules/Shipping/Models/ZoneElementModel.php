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
                'class' => CharField::className(),
                'primary' => true,
            ],
            'field_type' => [
                'class' => CharField::className(),
                'primary' => true,
            ],
            'zone' => [
                'field' => 'zoneid',
                'class' => ForeignField::className(),
                'modelClass' => ZoneModel::className(),
                'link' => ['zoneid' => 'zoneid'],
                'primary' => true,
            ],
            'shipping_rates' => [
                'class' => HasManyField::className(),
                'modelClass' => ShippingRateModel::className(),
                'link' => ['zoneid' => 'zoneid'],
            ]
        ];
    }
}