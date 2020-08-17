<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Core\Models\CountryModel;
use Modules\Sites\Models\BankAccountModel;
use Xcart\App\Form\Fields\DropDownField;
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