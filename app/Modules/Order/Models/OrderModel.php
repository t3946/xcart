<?php
namespace Modules\Order\Models;

use Modules\Core\Models\StateModel;
use Modules\Order\Helpers\OrderHelper;
use Modules\Product\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\App\Traits\FieldManagerCacheTrait;
use Xcart\Order;

class OrderModel extends Model
{
    use DataModelTrait, FieldManagerCacheTrait, AutoMetaTrait;

    public $last_activity;
    public $last_message;

    public static function getDataModelClass()
    {
        return Order::className();
    }

    public static function tableName()
    {
        return 'xcart_orders';
    }

    public static  function getFields()
    {
        return [
            'orderid' => [
                'class' => AutoField::className(),
            ],
            'date' => [
                'class' => TimestampField::className(),
            ],
            'groups' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupModel::className(),
                'link' => ['orderid' => 'orderid'],
            ],
            'details' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderDetailModel::className(),
                'link' => ['orderid' => 'orderid'],
            ],
            'tags' => [
                'class' => ManyToManyField::className(),
                'modelClass' => AttentionTagModel::className(),
                'through' => OrderAdditionalTagLinkModel::className(),
            ],
            'transactions' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderTransactionModel::className(),
                'link' => ['orderid' => 'orderid']
            ],
            'transactions_log' => [
                'class' => HasManyField::className(),
                'modelClass' => TransactionLogModel::className(),
                'link' => ['orderid' => 'orderid']
            ],
            'shipping_state' => [
                'field' => 's_state',
                'class' => ForeignField::className(),
                'modelClass' => StateModel::className(),
                'link' => [
                    's_state' => 'code',
                    's_country' => 'country_code'
                ]
            ],
        ];
    }

    /**
     * @param Order $model
     */
    public function afterFetchDataModel($model)
    {
        /** @var OrderGroupModel $group */
        foreach ($this->groups as $group)
        {
            $model->orderGroup = $group->getDataModel();
        }
    }

    public function getAdminUrl()
    {
        return sprintf(Order::ADMIN_ORDER_MODIFY_URL, $this->orderid);
    }


    public function getMaxEta()
    {
        $result = OrderHelper::getMaxEtaTimeByOrder([$this->orderid]);

        if (!empty($result)) {
            return $result[$this->orderid];
        }

        return null;
    }

    public function getCountEvents($user_id = null)
    {
        $result = OrderHelper::getCountEvents([$this->orderid], $user_id);

        if (!empty($result)) {
            return $result[$this->orderid];
        }

        return null;
    }
    public function getOrderNumber()
    {
        return $this->order_prefix . $this->orderid;
    }

    public function isAmazon()
    {
        return !empty($this->amazonorderid);
    }

    /**
     * @return ProductModel[]
     */
    public function getProducts()
    {
        return ProductModel::objects()
            ->filter(['order_details__orderid' => $this->orderid])
            ->all();
    }
}