<?php

namespace Modules\Core\Models;


use Modules\Shipping\Models\ZoneElementModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class CountryModel extends Model
{
    use AutoMetaTrait;

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