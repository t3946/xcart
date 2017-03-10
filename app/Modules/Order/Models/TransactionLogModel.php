<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\IntField;

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
            'date' => [
                'class' => IntField::className(),
                'null' => false
            ]
        ];
    }
}