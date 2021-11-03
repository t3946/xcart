<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\LinkField;
use Xcart\App\Form\Fields\PhoneField;
use Xcart\App\Form\Fields\UrlField;

class IncorporationForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets() : array
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

    public function getFields() : array
    {
        $entity = $this->getInstance();
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
                'class' => PhoneField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'inc_representative_phone' => [
                'class' => PhoneField::class,
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
            'inc_state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () use ($entity) {
                    $result[''] = '';
                    foreach (StateModel::objects()->filter(['country_code__in' => [$entity->inc_country ?? 'US']]) as $state) {
                        $result[$state->stateid] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
            ],
            'inc_login_url' => [
                'class' => UrlField::class,
                'extend' => 'Login URL'
            ],
        ];
    }
}