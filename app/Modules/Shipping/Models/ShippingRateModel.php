<?php

namespace Modules\Shipping\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\ShippingRate;

class ShippingRateModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return ShippingRate::className();
    }

    public static function tableName()
    {
        return 'xcart_shipping_rates';
    }

    public static function getFields()
    {
        return [
            'rateid' => [
                'class' => AutoField::className(),
            ],
            'shipping' => [
                'field' => 'shippingid',
                'class' => ForeignField::className(),
                'modelClass' => ShippingModel::className(),
                'link' => ['shippingid' => 'shippingid'],
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
            ],
            'zone_element_country' => [
                'class' => HasManyField::className(),
                'modelClass' => ZoneElementModel::className(),
                'link' => ['zoneid' => 'zoneid'],
                'extra' => ['field_type' => 'C']
            ],

        ];
    }
}