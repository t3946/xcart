<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class OptionNewModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_options';
    }

    public static  function getFields()
    {
        return [
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class
            ],
            'variants' => [
                'class' => HasManyField::class,
                'modelClass' => OptionVariantModel::class,
                'link' => ['id' => 'option_id'],
            ]
        ];
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}