<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Modules\Shipping\Models\ShippingModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
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
            'shippingModel' => [
                'field' => 'shippingid',
                'class' => ForeignField::className(),
                'modelClass' => ShippingModel::className(),
                'null' => false,
            ],

            'invoices' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupInvoiceModel::className(),
                'link' => ['orderid'=>'orderid', 'manufacturerid'=>'manufacturerid'],
            ],
            'memos' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupMemoModel::className(),
                'link' => ['orderid'=>'orderid', 'manufacturerid'=>'manufacturerid'],
            ],
        ];
    }

    private static $shippingModels = [];
    public function getShippingModel()
    {
        if (isset(self::$shippingModels[$this->shippingid])) {
            $this->shippingModel = self::$shippingModels[$this->shippingid];
            return self::$shippingModels[$this->shippingid];
        }

        self::$shippingModels[$this->shippingid] = $this->shippingModel;
        return self::$shippingModels[$this->shippingid];
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