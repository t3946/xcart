<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
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
                'class' => AutoField::class,
            ],
            'zone_element' => [
               'class' => ForeignField::class,
               'modelClass' => ZoneElementModel::class,
               'link' => ['zoneid' => 'zoneid'],
           ],
        ];
    }

    public function __toString()
    {
        return (string) $this->zone_name;
    }
}