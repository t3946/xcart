<?php

namespace Modules\Core\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class TelephoneAreaModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_Telephone_area_codes';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
        ];
    }
}