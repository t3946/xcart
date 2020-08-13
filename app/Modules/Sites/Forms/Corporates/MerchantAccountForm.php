<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Models\MerchantAccountModel;
use Xcart\App\Form\Fields\UrlField;
use Xcart\App\Form\ModelForm;

class MerchantAccountForm extends ModelForm
{
    public function getFieldsets()
    {
        return [
            '' => [
                'issuer',
                'number',
            ],
            'Merchant account website login' => [
                'url',
                'login',
                'password',
            ],
        ];
    }

    public function getFields()
    {
        return array_replace(
            parent::getFields(), [
                'url' => [
                    'class' => UrlField::class
                ]
            ]
        );
    }

    public function getModel()
    {
        return new MerchantAccountModel;
    }

    public function getName()
    {
        return 'Merchant account';
    }
}