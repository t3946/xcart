<?php


namespace Modules\Sites\Models;


use Doctrine\DBAL\Types\Type;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Admin\CorporatesAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

class CorporateModel extends Model
{
    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Name',
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'sqlType' => Type::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country of incorporation',
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province of incorporation',
            ],
            'registration_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Registration #',
            ],
            'incorporation_date' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Year and date of incorporation',
            ],
            'agent_company_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company name',
            ],
            'agent_contact_person' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Contact person',
            ],
            'agent_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Phone #',
            ],
            'agent_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Email',
            ],
            'formal_street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'formal_street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'formal_city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'formal_country_model' => [
                'field' => 'formal_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['formal_country' => 'code'],
                'sqlType' => Type::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'formal_state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'formal_zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'physical_street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'physical_street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'physical_city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'physical_country_model' => [
                'field' => 'physical_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'sqlType' => Type::STRING,
                'link' => ['physical_country' => 'code'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'physical_state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'physical_zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'mailing_street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'mailing_street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'mailing_city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'mailing_country_model' => [
                'field' => 'mailing_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['mailing_country' => 'code'],
                'sqlType' => Type::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'mailing_state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'mailing_zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'inc_company_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company name',
            ],
            'inc_street_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'inc_street_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'inc_city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'inc_country_model' => [
                'field' => 'inc_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['inc_country' => 'code'],
                'sqlType' => Type::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'inc_state' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'inc_zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'inc_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Inc service company phone #',
            ],
            'inc_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Inc service company email',
            ],
            'inc_representative_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Representative name',
            ],
            'inc_representative_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Representative phone #',
            ],
            'inc_representative_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Representative email',
            ],
            'inc_login_url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login URL',
            ],
            'inc_login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login/username',
            ],
            'inc_password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Password',
            ],
            'bank_accounts' => [
                'class' => HasManyField::class,
                'modelClass' => BankAccountModel::class,
                'link' => ['id' => 'corporate_id']
            ]
        ];
    }

    public function getAdminUrl($section = 1): string
    {
        if ($section !== null) {
            return Xcart::app()->router->url('admin:update_section', [
                    'pk' => $this->pk,
                    'section' => $section,
                    'module' => 'Sites',
                    'admin' => CorporatesAdmin::classNameShort(),
                ]
            );
        }
        return '';
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}