<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property ProductModel product
 * @property string shopping_status
 */
class GoogleProductsModel extends Model
{
    public const SHOPPING_STATUS_APPROVED = 'approved';
    public const SHOPPING_STATUS_DISAPPROVED = 'disapproved';

    public static function tableName()
    {
        return 'google_products';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'primary' => true,
            ],
            'shopping_status' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
        ];
    }
}