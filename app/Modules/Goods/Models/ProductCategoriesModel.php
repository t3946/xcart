<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
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
            'category' => [
                'field' => 'categoryid',
                'class' => ForeignField::class,
                'modelClass' => CategoryModel::class,
                'link' => ['categoryid' => 'categoryid'],
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'main' => [
                'class' => CharField::class,
                'primary' => false,
                'null' => false,
                'default' => 'N'
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
        ];
    }
}