<?php

namespace Modules\Subscribe\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class SubscriberModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,

            'email' => [
                'class' => CharField::class,
                'null' => false,
            ],

            'sfid' => [
                'class' => IntField::class,
                'null' => false,
            ],

            'subscribe' => [
                'class' => BooleanField::class,
                'default' => false,
            ],

            'unsubscribe' => [
                'class' => BooleanField::class,
                'default' => false,
            ],

            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ],

            'nonce' => [
                'class' => CharField::class,
                'default' => '',
            ],
        ];
    }
}