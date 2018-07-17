<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
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
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'required' => true,
            ],
            'option' => [
                'field' => 'option_id',
                'class' => ForeignField::class,
                'modelClass' => OptionNewModel::class,
                'link' => ['option_id' => 'id'],
                'required' => true,
            ],
            'variants' => [
                'class' => HasManyField::class,
                'modelClass' => ProductOptionVariantModel::class,
                'link' => ['id' => 'option_id'],
            ],
        ];
    }

    public function __toString(): string
    {
        return (string) $this->option->name;
    }
}