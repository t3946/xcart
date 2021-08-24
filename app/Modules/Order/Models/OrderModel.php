<?php

namespace Modules\Order\Models;

use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Modules\Amazon\Models\AmazonListInboundShipment;
use Modules\Cart\Models\CartModel;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\StateModel;
use Modules\GeoIp\Models\GeoipLitecityLocationModel;
use Modules\Order\Helpers\FraudCheckFAHelper;
use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Order\Helpers\OrderEventHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Helpers\PhoneHelper;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\Field;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\ModelFieldInterface;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Order;

/**
 * @property array|null purchase_order
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
 * @property string|null non_us_confirmation
 * @property string orig_po
 * @property string po_number
 * @property string firstname
 * @property string login_last_opened_or_saved
 * @property string|int time_last_opened_or_saved
 * @property int storefrontid
 * @property mixed transactions
 * @property mixed b_company
 * @property mixed b_firstname
 * @property mixed phone
 * @property mixed b_state
 * @property mixed b_city
 * @property mixed b_address
 * @property mixed email
 * @property mixed phone_ext
 * @property mixed b_zipcode
 * @property mixed transactions_log
 * @property mixed tracking_all_filled
 * @property int tracking_fill_time
 * @property bool track_sms
 * @property bool billing_same_shipping
 * @property mixed|Field|FileField|ModelFieldInterface|null date
 * @property mixed|Field|FileField|ModelFieldInterface|null b_country
 * @property OrderExtraModel extra_model
 * @property StateModel billing_state
 * @property StateModel shipping_state
 * @property SiteModel site
 * @property string order_prefix
 * @property mixed|Field|FileField|ModelFieldInterface currency
 * @property mixed|Field|FileField|ModelFieldInterface payment_method_model
 * @property mixed|Field|FileField|ModelFieldInterface overall_fraud_score
 * @property mixed|Field|FileField|ModelFieldInterface bare_fraud_score
 * @property mixed|Field|FileField|ModelFieldInterface dc_status
 * @property FraudStatusModel fraud_status_model
 * @property string fraud_status
 */
class OrderModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public const ORDER_TYPE_XCART = 'XCART';
    public const ORDER_TYPE_FBA = 'FBA';
    public const ORDER_TYPE_MFN = 'MFN';
    public const ORDER_TYPE_FB = 'FB';

    public const ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED = 'PV';
    public const ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND = 'PF';
    public const ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS = 'IP';
    public const ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED = 'NS';

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

    public static function getFields()
    {
        return [
            'orderid' => [
                'class' => AutoField::class,
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
            'fba_shipment' => [
                'class' => HasToOneField::class,
                'modelClass' => AmazonListInboundShipment::class,
                'link' => ['orderid' => 'order_id'],
                'null' => true,
            ],
            'cart' => [
                'field' => 'cart_number',
                'class' => ForeignField::class,
                'modelClass' => CartModel::class,
                'link' => ['cart_number' => 'id'],
                'null' => true,
                'default' => null,
            ],
            'groups' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['orderid' => 'orderid'],
            ],
            'tags' => [
                'class' => ManyToManyField::class,
                'modelClass' => AttentionTagModel::class,
                'through' => OrderAdditionalTagLinkModel::class,
            ],
            'transactions' => [
                'class' => HasManyField::class,
                'modelClass' => OrderTransactionModel::class,
                'link' => ['orderid' => 'orderid']
            ],
            'transactions_log' => [
                'class' => HasManyField::class,
                'modelClass' => TransactionLogModel::class,
                'link' => ['orderid' => 'orderid']
            ],
            'shipping_state' => [
                'field' => 's_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'sqlType' => Types::STRING,
                'link' => [
                    's_state' => 'code',
                    's_country' => 'country_code'
                ]
            ],
            'shipping_country' => [
                'field' => 's_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Types::STRING,
                'link' => ['s_country' => 'code']
            ],
            'billing_state' => [
                'field' => 'b_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'sqlType' => Types::STRING,
                'link' => [
                    'b_state' => 'code',
                    'b_country' => 'country_code'
                ]
            ],
            'billing_country' => [
                'field' => 'b_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Types::STRING,
                'link' => ['b_country' => 'code']
            ],
            'cb_status_model' => [
                'field' => 'cb_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['cb_status' => 'code'],
                'sqlType' => Types::STRING,
                'null' => true,
            ],
            'dc_status_model' => [
                'field' => 'dc_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['dc_status' => 'code'],
                'sqlType' => Types::STRING,
                'null' => true,
            ],
            'bd_status_model' => [
                'field' => 'bd_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['bd_status' => 'code'],
                'sqlType' => Types::STRING,
                'null' => true,
            ],
            'd2a_status_model' => [
                'field' => 'd2a_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusModel::class,
                'link' => ['d2a_status' => 'code'],
                'sqlType' => Types::STRING,
                'null' => true,
            ],
            'fraud_status_model' => [
                'field' => 'fraud_status',
                'class' => ForeignField::class,
                'modelClass' => FraudStatusModel::class,
                'link' => ['fraud_status' => 'code'],
                'sqlType' => Types::STRING,
                'null' => false,
            ],
            'notification' => [
                'field' => 'cb_status',
                'class' => ForeignField::class,
                'modelClass' => OrderStatusNotificationModel::class,
                'link' => ['cb_status' => 'code'],
                'sqlType' => Types::STRING,
            ],
            'site' => [
                'field' => 'storefrontid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
                'null' => true,
            ],
            'detail_models' => [
                'class' => HasManyField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['orderid' => 'orderid'],
            ],
            'extra' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => ''
            ],
            'extra_info' => [
                'class' => HasManyField::class,
                'modelClass' => OrderExtrasModel::class,
                'link' => ['orderid' => 'orderid'],
            ],
            'payment_method_model' => [
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
            'track_sms' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'billing_same_shipping' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'is_new_checkout' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'order_type' => [
                'class' => CharField::class,
                'default' => self::ORDER_TYPE_XCART,
                'null' => false,
                'choices' => [
                    self::ORDER_TYPE_XCART,
                    self::ORDER_TYPE_MFN,
                    self::ORDER_TYPE_FBA,
                    self::ORDER_TYPE_FB,
                ]
            ],
            'bare_fraud_score' => [
                'class' => FloatField::class,
                'default' => 0
            ],
        ];
    }

    /**
     * @param Order $model
     */
    public function afterFetchDataModel($model)
    {
        /** @var OrderGroupModel $group */
        foreach ($this->groups as $group) {
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

    public function getOrderNumber(): string
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
    public function getProducts(): array
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

    public function getAddressInfo(): array
    {
        if ($this->s_country) {
            $s_state = $this->shipping_state ?: $this->s_state;
        }
        $info[] = [
            'address' => explode("\n", $this->s_address, 2),
            'firstname' => $this->s_firstname,
            'company' => $this->s_company,
            'city' => $this->s_city,
            'state' => $s_state ?? $this->s_state,
            'country' => $this->shipping_country,
            'zipcode' => $this->s_zipcode,
        ];

        if ($this->b_firstname || $this->b_company || $this->b_address || $this->b_city || $this->b_state || $this->b_country || $this->b_zipcode !== null) {
            if ($this->b_country) {
                $b_state = $this->billing_state ?: $this->b_state;
            }
            $info[] = [
                'address' => explode("\n", $this->b_address, 2),
                'firstname' => $this->b_firstname,
                'company' => $this->b_company,
                'city' => $this->b_city,
                'state' => $b_state ?? $this->b_state,
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

    public function getEstimatedDeliveryDate(): ?DateTime
    {
        $max_day = null;

        foreach ($this->groups as $group) {
            /** @var ShippingModel $shipping */
            if (!$shipping = $group->shippingModel) {
                break;
            }
            $max_day = max($max_day, $shipping->days_max);
        }

        if ($max_day <= 0) {
            $max_day = 14;
        }

        $order_date = (new DateTime)->setTimestamp($this->date);

        return $order_date->add(new DateInterval("P{$max_day}D"));
    }

    public function getOrderHash()
    {
        return OrderHelper::getOrderHash([$this->orderid, $this->s_zipcode, $this->email]);
    }

    public function getCxDateTime($is_now = true): ?DateTime
    {
        if ($this->b_country && $this->b_state) {
            $state = $this->billing_state;
        } elseif ($this->s_country && $this->s_state) {
            $state = $this->shipping_state;
        }

        if ($time_zone = $state->timezone) {
            $date = new DateTime('now', new DateTimeZone($time_zone));
            if ($is_now) {
                return $date;
            }
            $date->setTimestamp($this->date);
            return $date;
        }
        return null;
    }

    public function getBillingAddressString(): string
    {
        [, $b_address] = $this->getAddressInfo();
        return $b_address['address'][0] . ($b_address['address'][1] ? ", {$b_address['address'][1]}" : '') . ", {$b_address['city']}, {$b_address['state']->code}, {$b_address['zipcode']}";
    }

    public function getShippingAddressString(): string
    {
        [$s_address] = $this->getAddressInfo();
        return $s_address['address'][0] . ($s_address['address'][1] ? ", {$s_address['address'][1]}" : '') . ", {$s_address['city']}, {$s_address['state']->code}, {$s_address['zipcode']}";
    }

    public function getEmailDomain(): string
    {
        $userinfo_site_arr = explode('@', $this->email);
        return $userinfo_site_arr[1] ?? '';
    }

    public function getIp(): ?string
    {
        if ($extra = $this->extra_model) {
            return $extra->getIP();
        }
        return null;
    }

    public function getGeoLocation(): ?GeoipLitecityLocationModel
    {
        if ($extra = $this->extra_model) {
            return $extra->getGeoLocation();
        }
        return null;
    }

    public function getPhoneNormalized(): string
    {
        return PhoneHelper::getPhoneNormalized($this->phone, $this->b_country);
    }

    public function getOrderCancelLink()
    {
    }

    public function isPurchaseOrder()
    {
        return in_array($this->cb_status, [OrderStatusModel::ORDER_STATUS_UNPAID_PO, OrderStatusModel::ORDER_STATUS_INCOMPLETE_PO], true);
    }

    public function __toString()
    {
        return $this->getOrderNumber();
    }

    public function getRiskScore(): float
    {
        return BaseFraudCheckHelperV2::getRiskScore($this->total, $this->bare_fraud_score, $this->overall_fraud_score);
    }

    public function getTrackingNumbers(): array
    {
        $tracks = [];
        foreach ($this->groups as $group) {
            foreach ($group->trackings as $track) {
                $tracks[] = $track;
            }
        }
        return $tracks;
    }

    public function getLastAuthorizationTransaction(): ?OrderTransactionModel
    {
        return $this->transactions
            ->filter([
                'transaction_status' => OrderTransactionModel::STATUS_AUTHORIZED,
                'type' => OrderTransactionModel::TYPE_AUTHORIZATION,
            ])
            ->order(['-id'])
            ->limit(1)
            ->get();
    }

    public function getTransactionDescription(): string
    {
        return "S3 Stores, Inc. Order # {$this->getOrderNumber()}";
    }

    public function updateVerificationStatus(): void
    {
        if ($this->vn_status !== ($new_status = OrderHelper::getOrderVerificationStatus($this))) {
            OrderHelper::changeOrderVerificationStatus($this, $new_status);
        }
    }

    public function getAltItems(): array
    {
        if ($this->alt_items && $a = explode(',', $this->alt_items)) {
            return ProductModel::forsale()->filter(['productcode__in' => $a])->all();
        }
        return [];
    }

    public function getShippingCost(): float
    {
        return array_reduce($this->groups->all(), static fn($c, $i) => $c + $i->shipping_gross);
    }

    /**
     * @return array
     */
    public function getTaxes(): array
    {
        $res = [];
        foreach ($this->groups as $group) {
            foreach ($group->getTaxes() as $type => $val) {
                $res[$type] += $val;
            }
        }
        return $res;
    }

    public function getGoogleShippingAddress(): string
    {
        $result_address = '';
        if ($address = $this->getAddressInfo()) {
            foreach ($address as $key => $a) {
                $addr = $a['address'][0] . (!empty($a['address'][1]) ? " {$a['address'][1]}" : '') . " {$a['city']} {$a['state']} {$a['zipcode']}";
                $addr = str_replace([' ', '#', '&'], ['+', '', 'and'], $addr);
                if ($key) {
                    $result_address = $addr;
                }
            }
        }
        return $result_address;
    }

    public function getFirstTransaction()
    {
        if ($log = $this->transactions_log->limit(1)->order(['date'])->get()) {
            return $log->transaction;
        }
        return $this->transactions->limit(1)->order(['-date'])->get();
    }

    public function orderFraudCheck()
    {
        $overallFraudScore = $bareFraudScore = 0;
        /** @var BaseFraudCheckModelV2 $fraud */
        foreach (BaseFraudCheckModelV2::objects()->order(['orderby'])->filter(['active' => 'Y']) as $fraud) {
            [$fraud_result, $fraud_score, $additional_info, $manual_action] = $fraud->getScore($this);
            if (!is_null($fraud_result)) {
                [$orderFraud] = OrderBaseFraudCheckModelV2::objects()->updateOrCreate([
                    'order_id' => $this->orderid,
                    'question_id' => $fraud->id
                ], [
                    'manual_action' => $manual_action,
                    'fraud_score' => $fraud_score,
                    'fraud_result' => $fraud_result,
                    'additional_info' => $additional_info
                ]);
                $overallFraudScore += (float)$orderFraud->fraud_score;
                if ($fraud->question_code !== 'CHECK_TOTAL') {
                    $bareFraudScore += (float)$orderFraud->fraud_score;
                }
                $orderFraud->save();
            }
        }

        $fa_heler = new FraudCheckFAHelper($this);
/*        $fa_heler->test_data = true;*/
        $fa_heler->fetchBaseDataOrder();
        /** @var FraudFAQuestionModel $fraud_fa */
        foreach (FraudFAQuestionModel::objects()->order(['order_by']) as $fraud_fa) {
            [$fraud_result, $fraud_score, $info] = $fraud_fa->getScore($this, true, $fa_heler);
            /** @var OrderFraudFACheckModel $order_fraud_fa */
            [$order_fraud_fa] = OrderFraudFACheckModel::objects()->updateOrCreate([
                'order_id' => $this->orderid,
                'question_id' => $fraud_fa->question_id
            ], [
                'fraud_result' => $fraud_result,
                'fraud_score' => $fraud_score,
                'additional_info' => $info ?? null
            ]);
            $overallFraudScore += (float)$order_fraud_fa->fraud_score;
            $bareFraudScore += (float)$order_fraud_fa->fraud_score;
            $order_fraud_fa->save();
        }
        $this->overall_fraud_score = $overallFraudScore;
        $this->bare_fraud_score = $bareFraudScore;
        $this->save();
    }
}