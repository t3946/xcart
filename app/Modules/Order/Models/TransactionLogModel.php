<?php

namespace Modules\Order\Models;

use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;

class TransactionLogModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_transaction_logs';
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
                    OrderTransactionModel::STATUS_PENDING => 'Pending',
                    OrderTransactionModel::STATUS_AUTHORIZED => 'Authorized',
                    OrderTransactionModel::STATUS_CAPTURED => 'Captured',
                    OrderTransactionModel::STATUS_VOIDED => 'Voided',
                    OrderTransactionModel::STATUS_COMPLETED => 'Completed',
                    OrderTransactionModel::STATUS_EXPIRED => 'Expired',
                    OrderTransactionModel::STATUS_FAILED => 'Failed',
                    OrderTransactionModel::STATUS_REFUNDED => 'Refunded',
                    OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED => 'Partially Refunded',
                    OrderTransactionModel::STATUS_PARTIALLY_CAPTURED => 'Partially Captured',
                ]
            ],
            'date' => [
                'class' => UnixTimestampField::className(),
                'autoNowAdd' => true,
                'autoNow' => true,
                'null' => false
            ],
            'transaction_total' => [
                'class' => FloatField::className(),
                'null' => false,
                'default' => 0,
            ],
            'transaction_log' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => ''
            ],
            'user' => [
                'field' => 'login',
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'link' => ['login' => 'login'],
            ],
            'payment_method_model' => [
                'field' => 'paymentid',
                'class' => ForeignField::className(),
                'modelClass' => PaymentMethodModel::className(),
                'null' => false,
            ],
        ];
    }
}