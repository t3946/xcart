<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
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
                'class' => HasManyField::class,
                'modelClass' => OptionValueModel::class,
                'link' => ['classid' => 'classid'],
            ],

            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid']
            ],

            'classid' => [
                'class' => AutoField::class,
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
        return $this->is_modifier !== 'Y';
    }
}