<?php
namespace Modules\Brand\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class BrandStorefrontModel extends Model
{
    public static function tableName()
    {
        return 'xcart_brands_sf';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'brand' => [
                'field' => 'brandid',
                'class' => ForeignField::class,
                'modelClass' => BrandModel::class,
                'link' => ['brandid' => 'brandid'],
            ],
            'products_count' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
            'storefront' => [
                'field' => 'sfid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['sfid' => 'storefrontid'],
            ],
        ];
    }
}