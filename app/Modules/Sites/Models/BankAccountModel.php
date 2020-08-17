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
                'default' => null,
                'verboseName' => 'Bank name',
            ],
            'account_type' => [
                'class' => CharField::class,
                'choices' => [
                    'Checking' => 'Checking',
                    'Savings' => 'Savings',
                ],
                'null' => true,
                'default' => null,
                'verboseName' => 'Account type',
            ],
            'account_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Account number',
            ],
            'routing_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Routing number',
            ],
            'street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Bank phone #',
            ],
            'email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Bank email',
            ],
            'account_manager_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Account manager name',
            ],
            'account_manager_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Account manager phone #',
            ],
            'account_manager_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Account manager email',
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login URL',
            ],
            'login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login/username',
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Password',
            ],
        ];
    }
}