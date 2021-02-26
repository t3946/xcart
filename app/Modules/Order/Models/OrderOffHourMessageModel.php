<?php


namespace Modules\Order\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class OrderOffHourMessageModel extends Model
{
    public static function tableName()
    {
        return 'xcart_off_hours_messages';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid']
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'group' => [
                'class' => ForeignField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['manufacturerid' => 'manufacturerid', 'orderid' => 'orderid']
            ]
        ];
    }
}