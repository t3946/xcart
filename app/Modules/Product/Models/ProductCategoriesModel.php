<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductCategoriesModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_categories';
    }

    public static function getFields()
    {
        return [
            'categoryid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'productid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'main' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false,
                'default' => 'N'
            ],
            'orderby' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
        ];
    }
}