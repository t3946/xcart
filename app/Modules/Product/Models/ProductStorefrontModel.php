<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductStorefrontModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_sf';
    }

    public static function getFields()
    {
        return [
            'productid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
            'sfid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ]
        ];
    }
}