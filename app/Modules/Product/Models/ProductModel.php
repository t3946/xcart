<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class ProductModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_products';
    }

    public static function getFields()
    {
        return [
            'productid' => [
                'class' => AutoField::className(),
            ],
        ];
    }
}