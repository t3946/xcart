<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ProductOptionVariantModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_option_variants';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'product_option' => [
                'field' => 'product_option_id',
                'class' => ForeignField::class,
                'modelClass' => ProductOptionModel::class,
                'link' => ['product_option_id' => 'id'],
                'required' => true,
            ],
            'variant' => [
                'field' => 'variant_id',
                'class' => ForeignField::class,
                'modelClass' => OptionVariantModel::class,
                'link' => ['variant_id' => 'id'],
                'required' => true,
            ]
        ];
    }

    public function __toString(): string
    {
        return (string) $this->variant ? $this->variant->name : '';
    }
}