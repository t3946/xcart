<?php


namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductsSfMovesModel extends Model
{
    const RESOURCE_TYPE_CATEGORY = 'CS';
    const RESOURCE_TYPE_SITE = 'SF';
    const RESOURCE_TYPE_FILTER = 'FL';

    public static function tableName()
    {
        return 'products_sf_moves';
    }

    public static function getFields()
    {
        return [

            'batch_id' => [
                'class' => IntField::class,
                'default' => false
            ],

            'productid' => [
                'class' => IntField::class,
                'default' => false
            ],

            'resource_id' => [
                'class' => IntField::class,
                'default' => false
            ],

            'resource_type' => [
                'class' => CharField::class,
                'default' => false,
                'choices' => [
                    self::RESOURCE_TYPE_CATEGORY => 'Category',
                    self::RESOURCE_TYPE_SITE => 'Storefront',
                    self::RESOURCE_TYPE_FILTER => 'Filter'
                ]
            ],

            'resource_extra_value' => [
                'class' => CharField::class,
                'default' => false
            ]

        ];
    }
}