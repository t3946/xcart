<?php

namespace Modules\Distributor\Models;

use DateTime;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Models\ShippingRateModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Manufacturer;

/**
 * @property float price_coef_z
 * @property float d_minimum_order_amount_in_us
 * @property string d_minimum_order_amount
 */
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

    public function hasDefaultShippingZone(): bool
    {
        return ShippingRateModel::objects()
                ->filter([
                    'manufacturerid' => $this->manufacturerid,
                    'zoneid' => 0
                ])->count() > 0;
    }

    public function getDistributorTime(): DateTime
    {
        return (new DateTime())->setTimestamp(time() - $this->d_server_min_distributor_time * 60 * 60);
    }

    public function isGoodTimeToSendEmail(): bool
    {
        $result = false;

        $d_time = $this->getDistributorTime();

        $startTime = new DateTime('08:30');
        $endTime = new DateTime('16:30');

        if ($d_time >= $startTime && $d_time <= $endTime && !in_array(intval($d_time->format( 'N' )), [6, 7])) {
            if (!RequestAvailabilityOptionModel::objects()->get(['date_mm_dd_yyyy' => $d_time->format('m/d/Y'), 'active' => 'Y'])) {
                $result = true;
            }
        }

        return $result;
    }
}