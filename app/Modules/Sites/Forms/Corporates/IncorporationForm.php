<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\LinkField;
use Xcart\App\Form\Fields\UrlField;

class IncorporationForm extends CorporatesForm
{
    public function getFieldsets()
    {
        return [
            'Incorporation service company' => [
                'inc_company_name',
            ],
            'Inc service company address' => [
                'inc_street_address',
                'inc_street_address_line2',
                'inc_city',
                'inc_country_model',
                'inc_state',
                'inc_zip',
                'inc_phone',
                'inc_email',
                'inc_representative_name',
                'inc_representative_phone',
                'inc_representative_email',
            ],
            'Inc service company website login' => [
                'inc_login_url',
                'inc_login',
                'inc_password',
            ]
        ];
    }

    public function getFields()
    {
        $fields = parent::getFields();
        return [
            'inc_city' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_zip' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:100px;'],
            ],
            'inc_phone' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_representative_phone' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_email' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_representative_email' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_login' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_password' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_country_model' => [
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
            'inc_login_url' => [
                'class' => UrlField::class,
            ],
        ];
    }
}