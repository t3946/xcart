<?php
namespace Modules\Order\Models;

use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;

class OrderTransactionModel extends AutoMetaModel
{
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const STATUS_VOIDED = 'voided';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_EXPIRED = 'expired';

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
                    'AP' => 'Authorized',
                    'Pending' => 'Pending',
                    'authorized' => 'Authorized',
                    'voided' => 'Voided',
                    'completed' => 'Completed',
                    'Expired' => 'Expired',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
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
            'login' => [
                'field' => 'login',
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'link' => ['login' => 'login'],
            ],
        ];
    }
}