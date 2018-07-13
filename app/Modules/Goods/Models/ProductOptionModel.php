<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ProductOptionModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_options';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
        ];
    }
}