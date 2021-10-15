<?php
namespace Modules\Goods\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\ForeignField;
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
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'primary' => true,
                'null' => false,
            ],
            'sf' => [
                'field' => 'sfid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['sfid' => 'storefrontid'],
                'primary' => true,
                'null' => false,
                'default' => 0
            ]
        ];
    }
}