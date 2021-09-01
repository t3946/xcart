<?php

namespace Modules\Goods\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FeaturedProductsModel extends Model
{
    public static function tableName(): string
    {
        return  'xcart_featured_products';
    }

    public static function getFields(): array
    {
        return [
            'product' => [
                'class' => ForeignField::class,
                'field' => 'productid',
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
            ],
            'category' => [
                'class' => ForeignField::class,
                'field' => 'categoryid',
                'modelClass' => CategoryModel::class,
                'link' => ['categoryid' => 'categoryid']
            ],
            'product_order' => IntField::class,
            'avail' => [
                'class' => CharField::class,
                'length' => 1,
                'null' => false,
                'default' => 'Y'
            ],
            'site' => [
                'class' => ForeignField::class,
                'field' => 'storefrontid',
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
            ],
        ];
    }

}