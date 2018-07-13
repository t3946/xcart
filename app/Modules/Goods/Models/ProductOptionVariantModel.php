<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Model;

class ProductOptionVariantModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_option_variants';
    }

    public function __toString(): string
    {
        return (string )$this->name;
    }
}