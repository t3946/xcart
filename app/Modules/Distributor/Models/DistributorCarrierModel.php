<?php


namespace Modules\Distributor\Models;


use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DistributorCarrierModel extends Model
{
    public static function tableName()
    {
        return 'xcart_manufacturers_carrier';
    }

    public static function getFields()
    {
        return [
            'distributor' => [
                'field' => 'manufacturer_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturer_id' => 'manufacturerid'],
                'primary' => true,
            ],
            'carrier' => [
                'field' => 'carrier_id',
                'class' => ForeignField::class,
                'modelClass' => TrackingLinksCarrierModel::class,
                'link' => ['carrier_id' => 'carrier_id'],
                'primary' => true,
            ]
        ];
    }
}