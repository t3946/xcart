<?php

namespace Modules\Core\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class GeoLitecityBlocks extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_geo_litecity_blocks';
    }

    public static function getFields()
    {
        return [
            'startIpNum' => [
                'class' => IntField::className(),
                'primary' => true
            ],
        ];
    }
}