<?php

namespace Modules\Core\Models;


use Mindy\QueryBuilder\Expression;
use Modules\Core\CoreModule;
use Modules\Shipping\Models\ZoneElementModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\OneToOneField;
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
                'class' => CharField::class,
                'primary' => true,
            ],
            'zone_element' => [
                'class' => HasManyField::className(),
                'modelClass' => ZoneElementModel::className(),
                'link' => ['code' => 'field'],
                'extra' => ['field_type' => 'C']
            ],
            'name' => [
                'class' => CharField::class,
                'null' => true,
            ]
        ];
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}