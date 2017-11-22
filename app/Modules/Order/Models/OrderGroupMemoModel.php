<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroupMemos;

class OrderGroupMemoModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return OrderGroupMemos::className();
    }

    public static function tableName()
    {
        return 'xcart_order_group_memos';
    }

    public static function getFields()
    {
        return [
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['orderid' => 'orderid'],
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
                'primary' => true,
            ],
            'memo_number' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
                'primary' => true,
            ],
        ];
    }

}