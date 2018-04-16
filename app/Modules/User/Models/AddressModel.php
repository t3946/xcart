<?php

namespace Modules\User\Models;


use Modules\Core\Models\CountryModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AddressModel extends Model
{
    public static function tableName()
    {
        return 'user_address';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
                'null' => false,
            ],
            'full_name' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'company' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'address' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'address_2' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'null' => false,
            ],
            'zip' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'state' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'city' => [
                'class' => CharField::class,
                'null' => false,
            ],
        ];
    }
}