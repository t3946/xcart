<?php


namespace Modules\Account\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
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
            'product_id' => [
                'class' => CharField::class,
            ],
            'list' => [
                'field' => 'product_list_id',
                'class' => ForeignField::class,
                'modelClass' => ProductListsModel::class,
                'link' => ['product_lists_id' => 'product_lists_id'],
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 999999,
            ],
            'product_type' => [
                'class' => CharField::class,
            ],
            'comment' => [
                'class' => CharField::class,
            ],
            'priority' => [
                'class' => CharField::class,
            ],
            'needs' => [
                'class' => CharField::class,
            ],
            'has' => [
                'class' => CharField::class,
            ],
        ];
    }
}