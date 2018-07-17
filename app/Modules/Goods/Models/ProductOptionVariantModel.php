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
            'variant' => [
                'field' => 'option_id',
                'class' => ForeignField::class,
                'modelClass' => OptionVariant::class,
                'link' => ['variant_id' => 'id'],
                'required' => true,
            ]
        ];
    }

    public function __toString(): string
    {
        return (string )$this->variant->name;
    }
}