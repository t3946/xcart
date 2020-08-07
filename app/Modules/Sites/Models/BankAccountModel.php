<?php


namespace Modules\Sites\Models;


use Modules\Core\Models\CountryModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class BankAccountModel extends Model
{
    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'corporate' => [
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ],
            'bank_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'account_type' => [
                'class' => CharField::class,
                'choices' => [
                    'Checking' => 'Checking',
                    'Savings' => 'Savings',
                ],
                'null' => true,
                'default' => null
            ],
            'account_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'routing_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'null' => true,
                'default' => null
            ],
            'state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'account_manager_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'account_manager_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'account_manager_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
        ];
    }
}