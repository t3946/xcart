<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

class LogModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_logs';
    }
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'date' => [
                'class' => TimestampField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ],
        ];
    }
}