<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
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
            'id' => AutoField::class
        ];
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}