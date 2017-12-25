<?php
namespace Modules\Order\Models;

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

class TmpOrderGroupModel extends Model
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

            'cb_status_model' => [
                'field' => 'cb_status',
                'class' => ForeignField::className(),
                'modelClass' => OrderStatusModel::className(),
                'link' => ['cb_status' => 'code'],
                'null' => false,
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