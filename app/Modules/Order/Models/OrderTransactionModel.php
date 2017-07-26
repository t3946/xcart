<?php
namespace Modules\Order\Models;

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
            'transaction_status'  => [
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
                'autoNow' => true,
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
        ];
    }

    public function getProcessUrl($mode)
    {
        return Xcart::app()->router->url('order:transaction_process', ['order_id' => $this->orderid, 'mode' => $mode, 'id' => $this->id]);
    }
}