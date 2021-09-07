<?php

namespace Modules\Account\Models;

use Modules\User\Models\UserAccount\UserModel;
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
        ];
    }
}