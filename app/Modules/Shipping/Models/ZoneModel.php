<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ZoneModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_zones';
    }

    public static function getFields()
    {
        return [
            'zoneid' => [
                'class' => AutoField::className(),
            ],

        ];
    }
}