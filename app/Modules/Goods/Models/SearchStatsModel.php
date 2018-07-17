<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class SearchStatsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return "xcart_search_stats";
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'date_time' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ]
        ];
    }
}