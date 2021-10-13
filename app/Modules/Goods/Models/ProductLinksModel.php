<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductLinksModel extends Model
{
    public static function tableName()
    {
        return 'xcart_product_links';
    }

    public static function getFields()
    {
        return [
            'productid1' => [
                'class' => IntField::class,
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'productid2' => [
                'class' => IntField::class,
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
        ];
    }
}