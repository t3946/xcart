<?php

namespace Modules\Shipping\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\ShippingRate;

/**
 * @property ShippingModel shipping
 * @property int shippingid
 * @property int rateid
 *
 * @method null|float getShippingCharge
 * @method getShippingQuote()
 */
class ShippingRateModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return ShippingRate::class;
    }

    public static function tableName()
    {
        return 'xcart_shipping_rates';
    }

    public static function getFields()
    {
        return [
            'rateid' => [
                'class' => AutoField::class,
            ],
            'maxamount' => [
                'class' => IntField::class,
                'default' => 0,
            ],
            'minweight' => [
                'class' => DecimalField::class,
                'default' => 0,
            ],
            'maxweight' => [
                'class' => DecimalField::class,
                'default' => 999999.99,
            ],
            'mintotal' => [
                'class' => DecimalField::class,
                'default' => 0,
            ],
            'maxtotal' => [
                'class' => DecimalField::class,
                'default' => 999999.99,
            ],
            'rate' => [
                'class' => DecimalField::class,
                'default' => 0,
            ],
            'item_rate' => [
                'class' => DecimalField::class,
                'default' => 0,
            ],
            'shipping' => [
                'field' => 'shippingid',
                'class' => ForeignField::class,
                'modelClass' => ShippingModel::class,
                'link' => ['shippingid' => 'shippingid'],
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
            ],
            'zone_element_country' => [
                'class' => HasManyField::class,
                'modelClass' => ZoneElementModel::class,
                'link' => ['zoneid' => 'zoneid'],
                'extra' => ['field_type' => 'C']
            ],
            'cache_quotes' => [
                'class' => ManyToManyField::class,
                'modelClass' => ShippingCacheQuoteModel::class,
                'link' => ['rateid' => 'rate_id'],
            ],

        ];
    }
}