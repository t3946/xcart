<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Models\BankAccountModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\PhoneField;
use Xcart\App\Form\Fields\UrlField;
use Xcart\App\Form\ModelForm;

class BankAccountForm extends ModelForm
{
    public function getFieldsets()
    {
        return [
            '' => [
                'bank_name',
                'account_type',
                'account_number',
                'routing_number',
            ],
            'Bank address' => [
                'street_address',
                'street_address_line2',
                'city',
                'country',
                'state',
                'zip',
                'phone',
                'email',
                'account_manager_name',
                'account_manager_phone',
                'account_manager_email',
            ],
            'Bank website login' => [
                'url',
                'login',
                'password',
            ]
        ];
    }

    public function getFields()
    {
        $entity = $this->getInstance();

        return [
            'country' => [
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
            'state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () use ($entity) {
                    foreach (StateModel::objects()->filter(['country_code__in' => [$entity->country ?? 'US']]) as $state) {
                        $result[$state->code] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
            ],
            'phone' => [
                'class' => PhoneField::class
            ],
            'account_manager_phone' => [
                'class' => PhoneField::class
            ],
            'url' => [
                'class' => UrlField::class,
                'extend' => 'Login URL'
            ],
        ];
    }

    public function getModel()
    {
        return new BankAccountModel;
    }


    public function getName()
    {
        return 'Bank Account';
    }
}