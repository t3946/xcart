<?php


namespace Modules\Account\Models;


use Modules\Amazon\Models\AmazonListInboundShipmentItemModel;
use Modules\Goods\Models\ProductModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * Class ProductListsModel
 * @property int product_list_id
 * @property bool public
 * @property string name
 * @property string cache_url
 * @property string description
 * @property string recipient_name
 * @property ListItemsModel[]|Manager list_items
 * @property int address_id
 * @property ProductListsUserRoles[]|Manager user_list_roles
 * @property UserModel[]|Manager users
 * @property int birthday
 * @property string recipient_email
 * @package Modules\Account\Models
 */
class ProductListsModel extends Model
{
    public static function tableName(): string
    {
        return 'account_product_lists';
    }

    public static function getFields(): array
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
                'through' => ProductListsUserRoles::class
            ],
            'user_list_roles' => [
                'class' => HasManyField::class,
                'modelClass' => ProductListsUserRoles::class,
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
            'user_id' => [
                'class' => IntField::class,
            ],
        ];
    }

    public function getFrontendData(): array
    {
        foreach ($this->list_items->order(['order_by']) as $item_list_model) {
            $products[] = $item_list_model->getFrontendData();
        }
        foreach ($this->user_list_roles as $user) {
            $user_model = $user->user_model;
            $users[] = [
                'userId' => $user->user_id,
                'role' => $user->role,
                'email' => $user_model->email,
                'name' => $user_model->public_name ?? $user_model->name,
                'avatar_image' => $user_model->avatar_image->getUrl(),
            ];
        }
        
        $owner = UserModel::objects()->get(['user_id' => $this->user_id]);

        return [
            'description' => $this->description,
            'recipientName' => $this->recipient_name,
            'recipientEmail' => $this->recipient_email,
            'productListId' => $this->pk,
            'product_list_id' => $this->pk,
            'cacheUrl' => $this->cache_url,
            'birthday' => $this->birthday,
            'addressId' => $this->address_id,
            'name' => $this->name,
            'products' => $products ?? [],
            'users' => $users ?? [],
            'owner' => [
                'userId' => $owner->user_id,
                'role' => 'owner',
                'email' => $owner->email,
                'name' => $owner->public_name ?? $owner->name,
                'avatar_image' => $owner->avatar_image->getUrl(),
            ],
        ];
    }
}