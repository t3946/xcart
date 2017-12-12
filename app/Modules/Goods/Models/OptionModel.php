<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class OptionModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_classes';
    }

    public static  function getFields()
    {
        return [

            'values' => [
                'class' => HasManyField::className(),
                'modelClass' => OptionValueModel::className(),
                'link' => ['classid' => 'classid'],
            ],

            'classid' => [
                'class' => AutoField::className(),
            ],
        ];
    }

    public function getFrontendName()
    {
        return $this->classtext;
    }

    public function getActiveValues()
    {
        return $this->values->filter(['avail' => 'Y']);
    }

    public function hasVariants()
    {
        return $this->is_modifier != 'Y';
    }
}