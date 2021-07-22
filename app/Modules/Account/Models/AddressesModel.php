<?php


namespace Modules\Account\Models;


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
            'addresses_id' => [
                'class' => AutoField::class,
            ],
            'full_name' => [
                'class' => CharField::class,
            ],
            'country' => [
                'class' => CharField::class,
            ],
            'phone_number' => [
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
            'state' => [
                'class' => CharField::class,
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
        ];
    }
}