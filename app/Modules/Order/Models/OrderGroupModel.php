<?php
namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Models\ShippingModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroup;

class OrderGroupModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return OrderGroup::className();
    }

    public static function tableName()
    {
        return 'xcart_order_groups';
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
            'shippingModel' => [
                'field' => 'shippingid',
                'class' => ForeignField::className(),
                'modelClass' => ShippingModel::className(),
                'null' => false,
            ],
            'cb_status_model' => [
                'class' => ForeignField::className(),
                'field' => 'cb_status',
                'sqlType' => Type::STRING,
                'modelClass' => OrderStatusModel::className(),
                'link' => ['cb_status' => 'code'],
                'null' => false,
            ],
            'dc_status_model' => [
                'class' => ForeignField::className(),
                'field' => 'dc_status',
                'sqlType' => Type::STRING,
                'modelClass' => OrderStatusModel::className(),
                'link' => ['dc_status' => 'code'],
                'null' => false,
            ],
            'detail_models' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderDetailModel::className(),
                'link' => ['orderid'=>'orderid', 'manufacturerid'=>'product_model__manufacturerid'],
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
            'refunds' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupRefundModel::className(),
                'link' => ['orderid'=>'orderid', 'manufacturerid'=>'manufacturerid'],
            ],
            'tracking' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => '',
            ],
            'accounting' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'manufacturer_data' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'OLD_accounting' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'amz_customer_notes' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
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

    private $productModels = null;
    public function getProductModels()
    {
        if (is_null($this->productModels)) {
            $this->productModels = ProductModel::objects()
                ->getQuerySet()
                ->join('inner join', 'xcart_order_details', ['productid' => 'od.productid'], 'od')
                ->filter(['manufacturerid' => $this->manufacturerid, 'od.orderid' => $this->orderid])
                ->all();
        }
        return $this->productModels;
    }

    public function getRefunds()
    {
        $refs = $this->refunds->all();
        return $refs ? array_sum(array_map(function($a){return $a->total_gross;}, $refs)) : 0;
    }
}