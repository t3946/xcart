<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class ZoneModel extends AutoMetaModel
{
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