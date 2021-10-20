<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class QuickPricingModel extends Model
{
    public static function tableName()
    {
        return "xcart_quick_prices";
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'primary' => true
            ],
            'price' => [
                'field' => 'priceid',
                'class' => ForeignField::class,
                'modelClass' => PricingModel::class,
                'link' => ['priceid' => 'priceid', 'variantid' => 'variantid'],
                'primary' => true
            ],
            'variantid' => [
                'class' => IntField::class,
                'primary' => true,
                'default' => 0,
            ],
            'membershipid' => [
                'class' => IntField::class,
                'default' => 0,
            ],
        ];
    }
}