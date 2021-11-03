<?php


namespace Modules\Account\Models;


use Modules\Amazon\Models\AmazonListInboundShipmentItemModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

class ProductListsModel extends Model
{
    public static function tableName()
    {
        return 'account_product_lists';
    }

    public static function getFields()
    {
        return [
            'product_list_id' => [
                'class' => AutoField::class,
            ],
            'public' => [
                'class' => BooleanField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],
            'cache_url' => [
                'class' => CharField::class,
            ],
            'description' => [
                'class' => CharField::class,
            ],
            'recipient_name' => [
                'class' => CharField::class,
            ],
            'recipient_email' => [
                'class' => CharField::class,
            ],
            'birthday' => [
                'class' => IntField::class,
            ],
            'users' => [
                'class' => ManyToManyField::class,
                'modelClass' => UserModel::class,
                'through' => UserListModel::class
            ],
             'user_list_roles' => [
                'class' => HasManyField::class,
                 'modelClass' => UserListModel::class,
                 'link' => ['product_list_id' => 'product_list_id']
            ],
            'list_items' => [
                'class' => HasManyField::class,
                'modelClass' => ListItemsModel::class,
                'link' => ['product_list_id' => 'product_list_id']
            ],
            'address_id' => [
                'class' => IntField::class,
            ],
        ];
    }
}