<?php

namespace Modules\Core\Models;


use Mindy\QueryBuilder\Expression;
use Modules\Shipping\Models\ZoneElementModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;

class CountryModel extends AutoMetaModel
{
    public static $codes = [
        'United States' => 'US',
        'Canada' => 'CA',
    ];

    public static function tableName()
    {
        return 'xcart_countries';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => AutoField::className(),
            ],
            'zone_element' => [
                'class' => HasManyField::className(),
                'modelClass' => ZoneElementModel::className(),
                'link' => ['code' => 'field'],
                'extra' => ['field_type' => 'C']
            ],
        ];
    }
}