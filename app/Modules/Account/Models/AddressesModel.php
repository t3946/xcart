<?php


namespace Modules\Account\Models;


use Doctrine\DBAL\Types\Types;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Forms\Models\EmailModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class AddressesModel extends Model
{
    public static function tableName()
    {
        return 'account_addresses';
    }

    public static function getFields()
    {
        return [
            'address_id' => [
                'class' => AutoField::class,
            ],
            'full_name' => [
                'class' => CharField::class,
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Types::STRING,
                'link' => ['country' => 'code'],
            ],
            'phone_number' => [
                'class' => CharField::class,
            ],
            'phone_ext' => [
                'class' => CharField::class,
            ],
            'street' => [
                'class' => CharField::class,
            ],
            'detailed' => [
                'class' => CharField::class,
            ],
            'city' => [
                'class' => CharField::class,
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['state' => 'stateid'],
            ],
            'zip' => [
                'class' => CharField::class,
            ],
            'is_default' => [
                'class' => BooleanField::class,
            ],
            'delivery_type' => [
                'field' => 'delivery_type_id',
                'class' => ForeignField::class,
                'modelClass' => DeliveryTypesModel::class,
                'link' => ['delivery_type_id' => 'delivery_type_id'],
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'user_id'],
            ],
            'address_type' => [
                'class' => CharField::class,
                'default' => 'shipping',
            ],
        ];
    }
}