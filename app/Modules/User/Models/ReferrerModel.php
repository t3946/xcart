<?php

namespace Modules\User\Models;


use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class ReferrerModel extends AutoMetaModel
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