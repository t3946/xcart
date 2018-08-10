<?php

namespace Modules\Distributor\Models;

use DateTime;
use Modules\Goods\Models\ProductModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Shipping\Models\ShippingRateModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
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

    public static function getDataModelClass(): string
    {
        return Manufacturer::class;
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
            'manufacturer' => [
                'class' => CharField::class
            ],
            'shipping_rates' => [
                'class' => HasManyField::className(),
                'modelClass' => ShippingRateModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'reduce_extra_margin' => [
                'class' => BooleanCharField::class,
            ],
            'max_extra_margin' => [
                'class' => FloatField::class,
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
        return WorkingTimeHelper::workingDayTime($this->getDistributorTime());
    }

    public function checkMinimalAmount($subtotal = 0): bool
    {
        return $this->getMinimalAmount() < $subtotal;
    }

    public function getMinimalAmount(): float
    {
        if ($this->d_minimum_order_amount === 'applies_to_all_orders' && $this->d_for_orders_below_min_order_amount === 'are_rejected' && $this->d_minimum_order_amount_in_us) {
            return (float)$this->d_minimum_order_amount_in_us;
        }

        return 0;
    }

    public function __toString()
    {
        return (string)$this->manufacturer;
    }
}