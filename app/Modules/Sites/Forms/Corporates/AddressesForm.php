<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Xcart\App\Form\Fields\DropDownField;

class AddressesForm extends CorporatesForm
{
    public function getFieldsets()
    {
        return [
            'Registered agent information' => [
                'agent_company_name',
                'agent_contact_person',
                'agent_phone',
                'agent_email',
            ],
            'Formal corporate (Registered agent) address' => [
                'formal_street_address',
                'formal_street_address_line2',
                'formal_city',
                'formal_country_model',
                'formal_state',
                'formal_zip',
            ],
            'Physical address' => [
                'physical_street_address',
                'physical_street_address_line2',
                'physical_city',
                'physical_country_model',
                'physical_state',
                'physical_zip',
            ],
            'Mailing address' => [
                'mailing_street_address',
                'mailing_street_address_line2',
                'mailing_city',
                'mailing_country_model',
                'mailing_state',
                'mailing_zip',
            ]
        ];
    }

    public function getFields()
    {
        return [
            'formal_country_model' => [
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
            'physical_country_model' => [
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
            'mailing_country_model' => [
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
        ];
    }


}