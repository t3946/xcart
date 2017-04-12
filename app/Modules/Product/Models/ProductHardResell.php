<?php

namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\IntField;

class ProductHardResell extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_products_hard_resell';
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'class' => IntField::className(),
                'primary' => true
            ],
        ];
    }

}