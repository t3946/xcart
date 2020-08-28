<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Form\Fields\DropDownField;

class AccountingServiceFrom extends CorporatesForm
{
    public function getFieldsets()
    {
        return [
            '' => [
                'accounting_company_name'
            ],
            'Accounting service contact information' => [
                'accounting_company_phone',
                'accounting_company_email',
                'accountant_name',
                'accountant_phone',
                'accountant_email',
                'secretary_name',
                'secretary_phone',
                'secretary_email',
            ],
            'Accounting service company address' => [
                'accounting_company_address',
                'accounting_company_address_line2',
                'accounting_company_city',
                'accounting_company_country',
                'accounting_company_state',
                'accounting_company_zip'
            ],
        ];
    }

    public function getFields()
    {
        $entity = $this->getInstance();

        return [
            'accounting_company_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () {
                    foreach (CountryModel::objects()->order(['name']) as $country) {
                        $result[$country->code] = (string)$country;
                    }
                    return $result ?? [];
                },
            ],
            'accounting_company_state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () use ($entity) {
                    foreach (StateModel::objects()->filter(['country_code__in' => [$entity->accounting_company_country ?? 'US']]) as $state) {
                        $result[$state->code] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
            ],
        ];
    }

    public function getName()
    {
        return 'Accounting service company';
    }
}