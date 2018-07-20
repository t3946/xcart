<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class OptionVariantModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_option_variants';
    }

    public static function getFields()
    {
        return [
            'option' => [
                'field' => 'option_id',
                'class' => ForeignField::class,
                'modelClass' => OptionNewModel::class,
                'link' => ['option_id' => 'id'],
                'required' => true,
            ],
        ];
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}