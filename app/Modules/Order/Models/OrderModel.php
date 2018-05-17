<?php
namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Cart\Models\CartModel;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Order\Helpers\OrderEventHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Order;

/**
 * @property string|null purchase_order
 * @property string s_address
 * @property string s_firstname
 * @property string s_company
 * @property string s_city
 * @property string s_state
 * @property string s_country
 * @property string s_zipcode
 * @property int orderid
 * @property int paymentid
 * @property float total
 * @property float discount
 * @property float tax
 * @property float shipping_cost
 * @property int|null user_id
 * @property float coupon_discount
 * @property mixed|string cb_status
 * @property int|mixed subtotal
 * @property Manager|OrderDetailModel[] detail_models
 * @property Manager|OrderGroupModel[] groups
 * @property PaymentMethodModel payment_method
 * @property mixed|null non_us_confirmation
 */
class OrderModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public $last_activity;
    public $last_message;

    public static function getDataModelClass(): string
    {
        return Order::class;
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
                'class' => OneToOneField::class,
                'modelClass' => OrderExtraModel::class,
                'link' => ['orderid' => 'order_id'],
                'null' => true,
            ],
            'cart' => [
                'field' => 'cart_number',
                'class' => ForeignField::class,
                'modelClass' => CartModel::class,
                'link' => ['cart_number' => 'id'],
                'null' => true
            ],
            'groups' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupModel::className(),
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
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'sqlType' => Type::STRING,
                'link' => [
                    's_state' => 'code',
                    's_country' => 'country_code'
                ]
            ],
            'shipping_country' => [
                'field' => 's_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Type::STRING,
                'link' => ['s_country' => 'code']
            ],
            'billing_state' => [
                'field' => 'b_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'sqlType' => Type::STRING,
                'link' => [
                    'b_state' => 'code',
                    'b_country' => 'country_code'
                ]
            ],
            'billing_country' => [
                'field' => 'b_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Type::STRING,
                'link' => ['b_country' => 'code']
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
            'notification' => [
                'field' => 'cb_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusNotificationModel::class,
                'link' => ['cb_status' => 'code'],
                'sqlType' => Type::STRING,
            ],
            'detail_models' => [
                'class' => HasManyField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['orderid' => 'orderid'],
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
            'details' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'order_prefix' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'is_mobile_checkout' => [
                'class' => BooleanCharField::class,
                'null' => false,
            ],
            'non_us_confirmation' => [
                'class' => BooleanCharField::class,
                'null' => false,
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

    public function getAddressInfo() : array
    {
        $info[] = [
            'address' => explode(PHP_EOL, $this->s_address, 2),
            'firstname' => $this->s_firstname,
            'company' => $this->s_company,
            'city' => $this->s_city,
            'state' => $this->shipping_state ?: $this->s_state,
            'country' => $this->shipping_country,
            'zipcode' => $this->s_zipcode,
        ];

        if ($this->b_firstname || $this->b_company || $this->b_address || $this->b_city || $this->b_state || $this->b_country || $this->b_zipcode !== null) {
            $info[] = [
                'address' => explode(PHP_EOL, $this->b_address, 2),
                'firstname' => $this->b_firstname,
                'company' => $this->b_company,
                'city' => $this->b_city,
                'state' => $this->billing_state ?: $this->b_state,
                'country' => !empty($this->b_country) ? $this->billing_country : null,
                'zipcode' => $this->b_zipcode,
            ];
        }

        return $info;
    }

    public function isBillingAddressDiff(): bool
    {
        [$a_s, $a_b] = $this->getAddressInfo();

        if (!$a_b) {
            return false;
        }

        return !empty(array_diff($a_s, $a_b));
    }

    public function isCanadianShipping(): bool
    {
        [$shipping] = $this->getAddressInfo();

        return $shipping['country']->code === 'CA' && $this->groups->exclude(['manufacturer__m_country' => 'CA'])->count();
    }
}