<?php


namespace Modules\Account\Models;


use Modules\Forms\Models\EmailModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class UserAddressModel extends Model
{
    public static function tableName()
    {
        return 'user_address';
    }

    public static function getFields()
    {
        return [
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'primary' => true,
                'link' => ['user_id' => 'user_id'],
            ],
            'address' => [
                'field' => 'address_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'primary' => true,
                'link' => ['address_id' => 'address_id'],
            ],
        ];
    }
}