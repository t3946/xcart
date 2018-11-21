<?php

namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

/**
 * @property OrderModel order
 */
class OrderTransactionModel extends Model
{
    use AutoMetaTrait;

    public const STATUS_AUTHORIZED = 'authorized';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CAPTURED = 'captured';
    public const STATUS_PENDING = 'pending';
    public const STATUS_VOIDED = 'voided';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_PARTIALLY_RUFUNDED = 'partially_refunded';
    public const STATUS_PARTIALLY_CAPTURED = 'partially_captured';

    public const TYPE_AUTHORIZATION = 'authorization';
    public const TYPE_REFUND = 'refund';
    public const TYPE_CAPTURE = 'capture';

    public static function tableName()
    {
        return 'xcart_order_transactions';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'transaction_status' => [
                'class' => CharField::className(),
                'default' => 'failed',
                'null' => false,
                'choices' => [
                    self::STATUS_PENDING => 'Pending',
                    self::STATUS_AUTHORIZED => 'Authorized',
                    self::STATUS_CAPTURED => 'Captured',
                    self::STATUS_VOIDED => 'Voided',
                    self::STATUS_COMPLETED => 'Completed',
                    self::STATUS_EXPIRED => 'Expired',
                    self::STATUS_FAILED => 'Failed',
                    self::STATUS_REFUNDED => 'Refunded',
                    self::STATUS_PARTIALLY_RUFUNDED => 'Partially Refunded',
                    self::STATUS_PARTIALLY_CAPTURED => 'Partially Captured',
                ]
            ],
            'type' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null,
                'choices' => [
                    self::TYPE_AUTHORIZATION => 'Authorization',
                    self::TYPE_REFUND => 'Refund',
                    self::TYPE_CAPTURE => 'Capture',
                ]
            ],
            'transaction_response' => [
                'class' => SerializeField::className(),
                'null' => true,
            ],
            'payment_method_model' => [
                'field' => 'paymentid',
                'class' => ForeignField::className(),
                'modelClass' => PaymentMethodModel::className(),
                'null' => false,
            ],
            'date' => [
                'class' => UnixTimestampField::className(),
                'autoNowAdd' => true,
                'autoNow' => false,
            ],
            'user' => [
                'field' => 'login',
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'link' => ['login' => 'login'],
                'sqlType' => Type::STRING,
            ],
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['orderid' => 'orderid'],
            ],
            'transaction_logs' => [
                'class' => HasManyField::className(),
                'modelClass' => TransactionLogModel::className(),
                'link' => ['order_transaction_id' => 'id'],
            ],
            'parent' => [
                'field' => 'parent_id',
                'class' => ForeignField::className(),
                'modelClass' => OrderTransactionModel::className(),
                'link' => ['parent_id' => 'id'],
            ],
            'child' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderTransactionModel::className(),
                'link' => ['id' => 'parent_id'],
            ],
        ];
    }

    public function getProcessUrl($mode)
    {
        return Xcart::app()->router->url('order:transaction_process', ['order_id' => $this->orderid, 'mode' => $mode, 'id' => $this->id]);
    }

    public function getLinks()
    {
        $result = [];
        if ($this->transaction_response['links']) {
            $result = array_filter($this->transaction_response['links'], function ($a) {
                return ($a['method'] == 'POST' && array_key_exists($a['rel'], OrderTransactionStore::$gatewayMethods));
            });
        }
        return $result;
    }

    /**
     * @return float
     */
    public function getAvailAmount()
    {
        $avail = round(abs($this->transaction_amount), 2);

        /** @var OrderTransactionModel $model */
        foreach ($models = $this->child->all() as $model) {
            if ($model->type !== self::TYPE_REFUND) {
                $avail -= round(abs($model->getAvailAmount()), 2);
            }
        }

        return $avail;
    }
}