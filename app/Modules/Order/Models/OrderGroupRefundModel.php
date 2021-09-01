<?php

namespace Modules\Order\Models;


use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class OrderGroupRefundModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_refund_groups';
    }

    public static function getFields()
    {
        return [
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'null' => false,
                'primary' => true,
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupRefundProductModel::class,
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ]
        ];
    }
}