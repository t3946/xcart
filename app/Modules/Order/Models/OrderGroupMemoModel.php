<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroupMemos;

class OrderGroupMemoModel extends AutoMetaModel
{
    use DataModelTrait;

    public static function getDataModelClass()
    {
        return OrderGroupMemos::className();
    }

    public static function tableName()
    {
        return 'xcart_order_group_memos';
    }

    public static function getPrimaryKeyName($asArray = false)
    {
        return ['orderid', 'manufacturerid', 'memo_number'];
    }

    public static function getFields()
    {
        return [
            'orderid' => [
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ],
            'manufacturerid' => [
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'memo_number' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
        ];
    }

}