<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class OrderStatusAvailabilityModel extends Model
{
    public static function tableName()
    {
        return 'xcart_order_status_availability';
    }

    public static function getFields()
    {
        return [
            'destination_status_model' => [
                'field' => 'destination_status_id',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['destination_status_id' => 'status_id'],
                'primary' => true
            ],
            'source_status_model' => [
                'field' => 'source_status_id',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['source_status_id' => 'status_id'],
                'primary' => true
            ]
        ];
    }
}