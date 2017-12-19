<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class FraudStatusModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_fraud_statuses';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::className(),
                'primary' => true
            ],
        ];
    }
}