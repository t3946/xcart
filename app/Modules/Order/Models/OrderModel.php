<?php
namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Core\Models\StateModel;
use Modules\Order\Helpers\OrderEventHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Fields\UnixTimestampField;
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
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'extra_model' => [
                'class' => ForeignField::class,
                'modelClass' => OrderExtraModel::class,
                'link' => ['orderid' => 'order_id'],
                'null' => true,
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
                'sqlType' => Type::STRING,
                'link' => [
                    's_state' => 'code',
                    's_country' => 'country_code'
                ]
            ],
            'cb_status_model' => [
                'field' => 'cb_status',
                'class' => ForeignField::className(),
                'modelClass' => OrderStatusModel::className(),
                'link' => ['cb_status' => 'code'],
                'sqlType' => Type::STRING,
                'null' => false,
                'default' => OrderStatusModel::ORDER_STATUS_QUEUED
            ],
            'dc_status_model' => [
                'field' => 'dc_status',
                'class' => ForeignField::className(),
                'modelClass' => OrderStatusModel::className(),
                'link' => ['dc_status' => 'code'],
                'sqlType' => Type::STRING,
                'null' => false,
                'default' => OrderStatusModel::ORDER_DC_STATUS_NOT_SHIPPED
            ],
            'bd_status_model' => [
                'field' => 'bd_status',
                'class' => ForeignField::className(),
                'modelClass' => OrderStatusModel::className(),
                'link' => ['bd_status' => 'code'],
                'sqlType' => Type::STRING,
                'null' => false,
                'default' => OrderStatusModel::ORDER_BD_STATUS_UNPAID
            ],
            'fraud_status_model' => [
                'field' => 'fraud_status',
                'class' => ForeignField::className(),
                'modelClass' => FraudStatusModel::className(),
                'link' => ['fraud_status' => 'code'],
                'sqlType' => Type::STRING,
                'null' => false,
            ],
            'extra' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => ''
            ],
            'extra_info' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderExtrasModel::className(),
                'link' => ['orderid' => 'orderid'],
            ],
            'payment_method' => [
                'field' => 'paymentid',
                'class' => ForeignField::class,
                'modelClass' => PaymentMethodModel::class,
                'link' => ['paymentid' => 'paymentid'],
                'null' => false,
            ],
            'giftcert_ids' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'shippingid' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'tracking' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'shipping_costs' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'taxes_applied' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'notes' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'customer_notes' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'shipping_groups' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
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

    public function getEventsMessage()
    {
        return OrderHelper::getCountEventsActiveUserQS()
            ->filter(['order_id' => $this->pk])
            ->select([])
            ->group([])
            ->order(['-created_at'])
            ->all();
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

    public function afterSave($owner, $isNew)
    {
        parent::afterSave($owner, $isNew);

        foreach ($this->getAttributes() as $attribute => $value) {
            OrderEventHelper::registerAfterSaveEvent($this->pk, $attribute, $value, $this->getOldAttribute($attribute));
        }
    }

    public function getAddress() : array
    {
        return explode(PHP_EOL, $this->s_address, 2);
    }
}