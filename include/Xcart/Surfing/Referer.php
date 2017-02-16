<?php

namespace Xcart\Surfing;


use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class Referer extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_referers';
    }

    public static function getFields()
    {
        return [
            'referer_id' => [
                'class' => AutoField::className(),
            ]
        ];
    }
}