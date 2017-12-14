<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

/**
 * @property float price
 */
class PricingModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_pricing';
    }

    public static function getFields()
    {
        return [
            'priceid' => [
                'class' => AutoField::className(),
                'primary' => true,
                'null' => false,
            ],

            'product' => [
                'field' => 'productid',
                'class' => HasManyField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['productid' => 'productid']
            ]
        ];
    }
}