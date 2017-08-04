<?php

namespace Modules\Order\Models;

use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\OrderTransaction;

class OrderTransactionModel extends AutoMetaModel
{
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CAPTURED = 'captured';
    const STATUS_PENDING = 'pending';
    const STATUS_VOIDED = 'voided';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PARTIALLY_RUFUNDED = 'partially_refunded';
    const STATUS_PARTIALLY_CAPTURED = 'partially_captured';

    const TYPE_AUTHORIZATION = 'authorization';
    const TYPE_REFUND = 'refund';
    const TYPE_CAPTURE = 'capture';

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
                'link' => ['parent_id' => 'id'],
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

    public function getAvailAmount()
    {
        $avail = abs($this->transaction_amount);

        foreach ($models = $this->child->all() as $model) {
            $avail -= abs($model->transaction_amount);
        }

        return $avail;
    }
}