<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class DecisionModel extends Model
{
    public const DECISION_TYPE_ESTIMATED_TIME_ARRIVAL = 1;

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

            'order_id' => [
                'class' => IntField::class,
                'null' => false,
            ],

            'type' => [
                'class' => IntField::class,
                'null' => false,
            ],

            'resolved' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],

            'options' => [
                'class' => JsonField::class,
            ],
        ];
    }
}