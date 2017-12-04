<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OrderFraudCheckModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_fraud_checks';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'additional_info' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ]
        ];
    }
}