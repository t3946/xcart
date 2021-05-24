<?php


namespace Modules\Shipping\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class ApproximationShippingModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_approximation_shipping_rates';
    }

    public static function getFields()
    {
        return [
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'primary' => true
            ],
            'state' => [
                'field' => 'state',
                'class' => CharField::class,
                'primary' => true
            ],
            'last_updated_date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ],
            'updated_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ],
            'shipping' => [
                'field' => 'shipping_id',
                'class' => ForeignField::class,
                'modelClass' => ShippingModel::class,
                'link' => ['shipping_id' => 'shippingid'],
                'primary' => true
            ]
        ];
    }
}