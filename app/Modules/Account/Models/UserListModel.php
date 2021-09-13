<?php

namespace Modules\Account\Models;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class UserListModel extends Model
{
    public static function tableName()
    {
        return 'account_user_list';
    }

    public static function getFields()
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
            ],
            'list_type' => [
                'class' => CharField::class,
            ],
        ];
    }
}