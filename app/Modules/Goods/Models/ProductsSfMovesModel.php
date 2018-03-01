<?php


namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductsSfMovesModel extends Model
{
    public static function tableName()
    {
        return 'products_sf_moves';
    }

    public static function getFields()
    {
        return [

            'batch_id' => [
                'class' => IntField::className(),
                'default' => false
            ],

            'productid' => [
                'class' => IntField::className(),
                'default' => false
            ],

            'resource_id' => [
                'class' => IntField::className(),
                'default' => false
            ],

            'resource_type' => [
                'class' => CharField::className(),
                'default' => false,
                'choices' => [
                    0 => 'CS',
                    1 => 'SF',
                    2 => 'FL'
                ]
            ],

            'resource_extra_value' => [
                'class' => CharField::className(),
                'default' => false
            ]

        ];
    }
}