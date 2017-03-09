<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroup;

class OrderGroupModel extends AutoMetaModel
{
    use DataModelTrait;

    public static function getDataModelClass()
    {
        return OrderGroup::className();
    }

    public static function tableName()
    {
        return 'xcart_order_groups';
    }

    public static function getPrimaryKeyName($asArray = false)
    {
        return ['orderid', 'manufacturerid'];
    }

    public static function getFields()
    {
        return [
            'order' => [
                'name' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
//                'link' => ['orderid', 'orderid'],
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'name' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
//                'link' => ['manufacturerid', 'manufacturerid'],
                'null' => false,
                'primary' => true,
            ],

            'invoices' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupInvoiceModel::className(),
                'link' => [['orderid', 'orderid'], ['manufacturerid', 'manufacturerid']],
            ],
            'memos' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupMemoModel::className(),
                'link' => [['orderid', 'orderid'], ['manufacturerid', 'manufacturerid']],
            ],
        ];
    }

    public function getPaymentMethodId()
    {
        return $this->acc_paymentid;
    }

    /**
     * @param OrderGroup $model
     */
    public function afterFetchDataModel($model)
    {

    }
}