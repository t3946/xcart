<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class DecisionModel extends Model
{
    public const DECISION_TYPE_ESTIMATED_TIME_ARRIVAL = 0;

    public static function tableName()
    {
        return 'account_decision';
    }

    public static function getFields()
    {
        return [
            'decision_id' => [
                'class' => AutoField::class,
            ],

            'type' => [
                'class' => IntField::class,
                'null' => false,
            ],

            'solved' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],

            'options' => [
                'class' => JsonField::class,
            ],

            'order_number' => [
                'class' => CharField::class,
            ],

            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],

            'updated' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
                'null' => false
            ]
        ];
    }
}