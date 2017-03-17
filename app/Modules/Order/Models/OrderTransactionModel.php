<?php
namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\SerializeField;

class OrderTransactionModel extends AutoMetaModel
{
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
                ]
            ],
            'transaction_response' => [
                'class' => SerializeField::className(),
                'null' => true,
            ]
        ];
    }
}