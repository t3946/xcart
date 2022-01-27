<?php

namespace Modules\Account\Models;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * Class UserListModel
 * @property ProductListsModel list_model
 * @property string role
 * @property string list_type
 * @property int user_id
 * @property UserModel user_model
 * @property string source
 * @package Modules\Account\Models
 */
class UserListModel extends Model
{
    public const SOURCE_CREATE_DEFAULT = 'default';
    public const SOURCE_CREATE_SIMPLE = 'simple';

    public static function tableName(): string
    {
        return 'account_user_list';
    }

    public static function getFields(): array
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'user_model' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'user_id'],
            ],
            'list_model' => [
                'field' => 'product_list_id',
                'class' => ForeignField::class,
                'modelClass' => ProductListsModel::class,
                'link' => ['product_list_id' => 'product_list_id'],
            ],
            'role' => [
                'class' => CharField::class,
                'default' =>'owner'
            ],
            'list_type' => [
                'class' => CharField::class,
                'default' =>'private'
            ],
            'source' => [
                'class' => CharField::class,
                'default' => 'simple'
            ]
        ];
    }
}