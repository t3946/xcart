<?php


namespace Modules\Account\Models;


use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
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
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'user_id'],
            ],
            'product_list_id' => [
                'class' => AutoField::class,
            ],
            'public' => [
                'class' => BooleanField::class,
            ],
            'name' => [
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
        ];
    }
}