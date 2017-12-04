<?php

namespace Modules\Distributor\Models;

use Modules\Product\Models\ProductModel;
use Modules\Shipping\Models\ShippingRateModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Manufacturer;

class DistributorModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return Manufacturer::className();
    }

    public static function tableName()
    {
        return 'xcart_manufacturers';
    }

//    public static function getPrimaryKeyName($asArray = false)
//    {
//        return ['manufacturerid'];
//    }

    public static function getFields()
    {
        return [
            'manufacturerid' => [
                'class' => AutoField::className()
            ],
            'shipping_rates' => [
                'class' => HasManyField::className(),
                'modelClass' => ShippingRateModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
        ];
    }


    /**
     * @param ProductModel $modelProduct
     * @return float
     */
    public function calculatePrice($modelProduct)
    {
        $price = 0;
        if ($this->price_coef_z) {
            $price = max(round(($modelProduct->cost_to_us * $this->price_coef_x + $this->price_coef_y) / $this->price_coef_z, 2), $modelProduct->map_price);
        }
        return $price;
    }

    public function hasDefaultShippingZone()
    {
        return ShippingRateModel::objects()
                ->filter(
                    [
                        'manufacturerid' => $this->manufacturerid,
                        'zoneid' => 0
                    ]
                )->count() > 0;
    }
}