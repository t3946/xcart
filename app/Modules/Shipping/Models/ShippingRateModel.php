<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\ShippingRate;

class ShippingRateModel extends AutoMetaModel
{
    use DataModelTrait;

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

        ];
    }
}