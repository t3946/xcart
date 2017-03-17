<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\SerializeField;

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
                    'AP' => 'Authorized',
                    'Pending' => 'Pending',
                    'authorized' => 'Authorized',
                    'voided' => 'Voided',
                    'completed' => 'Completed',
                    'Expired' => 'Expired',
                    'failed' => 'Failed',
                ]
            ],
            'date' => [
                'class' => IntField::className(),
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
            ]
        ];
    }
}