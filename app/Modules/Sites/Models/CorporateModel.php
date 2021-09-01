<?php


namespace Modules\Sites\Models;


use Doctrine\DBAL\Types\Types;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Admin\CorporatesAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
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
                'sqlType' => Types::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country of incorporation',
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['state' => 'stateid'],
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
                'sqlType' => Types::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'formal_state_model' => [
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['formal_state' => 'stateid'],
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
                'sqlType' => Types::STRING,
                'link' => ['physical_country' => 'code'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'physical_state_model' => [
                'field' => 'physical_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['physical_state' => 'stateid'],
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
                'sqlType' => Types::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'mailing_state_model' => [
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['mailing_state' => 'stateid'],
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
                'sqlType' => Types::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'inc_state_model' => [
                'field' => 'inc_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['inc_state' => 'statid'],
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
            'shares' => [
                'class' => IntField::class,
                'default' => 100000,
                'verboseName' => 'Total number of shares',
            ],
            'federal_tax_id_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Federal tax ID name',
            ],
            'federal_tax_id' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Federal tax ID',
            ],
            'federal_tax_url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login URL',
            ],
            'federal_tax_login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login/username',
            ],
            'federal_tax_password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Password',
            ],
            'federal_tax_year' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Federal tax year starts',
            ],
            'state_tax_id_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province tax ID name',
            ],
            'state_tax_id' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province tax ID',
            ],
            'state_tax_url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login URL',
            ],
            'state_tax_login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login/username',
            ],
            'state_tax_password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Password',
            ],
            'state_tax_year' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province tax year starts',
            ],
            'accounting_company_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company name',
            ],
            'accounting_company_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company phone #',
            ],
            'accounting_company_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company email',
            ],
            'accountant_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Accountant name',
            ],
            'accountant_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Accountant phone #',
            ],
            'accountant_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Accountant email',
            ],
            'secretary_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Secretary name',
            ],
            'secretary_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Secretary phone',
            ],
            'secretary_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Secretary email',
            ],
            'accounting_company_address' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address',
            ],
            'accounting_company_address_line2' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Street address (line 2)',
            ],
            'accounting_company_city' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'City',
            ],
            'accounting_company_country_model' => [
                'field' => 'accounting_company_country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['accounting_company_country' => 'code'],
                'sqlType' => Types::STRING,
                'null' => true,
                'default' => null,
                'verboseName' => 'Country',
            ],
            'accounting_company_state_model' => [
                'field' => 'accounting_company_state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['accounting_company_state' => 'statid'],
                'null' => true,
                'default' => null,
                'verboseName' => 'State/Province',
            ],
            'accounting_company_zip' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Zip/Postal code',
            ],
            'income_tax_period_starts_day' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'income_tax_period_starts_month' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'income_period_duration' => [
                'class' => CharField::class,
                'choices' => [
                    'year' => '1 year',
                    'quarter' => '1 quarter (3 months)',
                    'month' => '1 month',
                ],
                'null' => true,
                'default' => null,
                'verboseName' => 'Income tax period duration'
            ],
            'sales_tax_period_starts_day' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'sales_tax_period_starts_month' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'sales_period_duration' => [
                'class' => CharField::class,
                'choices' => [
                    'year' => '1 year',
                    'quarter' => '1 quarter (3 months)',
                    'month' => '1 month',
                ],
                'null' => true,
                'default' => null,
                'verboseName' => 'Sales tax period duration'
            ],
            'vat_tax_period_starts_day' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'vat_tax_period_starts_month' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'vat_period_duration' => [
                'class' => CharField::class,
                'choices' => [
                    'year' => '1 year',
                    'quarter' => '1 quarter (3 months)',
                    'month' => '1 month',
                ],
                'null' => true,
                'default' => null,
                'verboseName' => 'VAT tax period duration'
            ],
            'bank_accounts' => [
                'class' => HasManyField::class,
                'modelClass' => BankAccountModel::class,
                'link' => ['id' => 'corporate_id']
            ],
            'shareholders' => [
                'class' => HasManyField::class,
                'modelClass' => ShareHolderModel::class,
                'link' => ['id' => 'corporate_id']
            ],
            'income_tax_returns' => [
                'class' => HasManyField::class,
                'modelClass' => TaxReturnModel::class,
                'link' => ['id' => 'corporate_id'],
                'extra' => ['tax_type' => 'Income']
            ],
            'sales_tax_returns' => [
                'class' => HasManyField::class,
                'modelClass' => TaxReturnModel::class,
                'link' => ['id' => 'corporate_id'],
                'extra' => ['tax_type' => 'Sales']
            ],
            'vat_tax_returns' => [
                'class' => HasManyField::class,
                'modelClass' => TaxReturnModel::class,
                'link' => ['id' => 'corporate_id'],
                'extra' => ['tax_type' => 'VAT']
            ],
            'storefronts' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => CorporateStorefrontsModel::class,
                'verboseName' => 'Storefronts'
            ],
            'taxes' => [
                'class' => ManyToManyField::class,
                'modelClass' => TaxModel::class,
                'through' => CorporateTaxesModel::class,
                'verboseName' => 'Taxes'
            ],
        ];
    }

    public function getAdminUrl($section = 1): string
    {
        if ($section !== null && $this->pk !== null) {
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