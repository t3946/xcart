<?php


namespace Modules\Account\Models;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ListItemsModel extends Model
{
    public static function tableName()
    {
        return 'account_list_items';
    }

    public static function getFields()
    {
        return [
            'list_items_id' => [
                'class' => AutoField::class,
            ],
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
            ],
            'list' => [
                'field' => 'product_list_id',
                'class' => ForeignField::class,
                'modelClass' => ProductListsModel::class,
                'link' => ['product_lists_id' => 'product_lists_id'],
            ],
            'order_by' => [
                'class' => IntField::class,
            ],
        ];
    }
}