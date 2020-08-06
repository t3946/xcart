<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Models\CorporateModel;
use Xcart\App\Form\ModelForm;

class CorporatesForm extends ModelForm
{
    public static array $sections = [
        [
            'shareholders' => [
                'title' => 'Shareholders'
            ],
            'addresses' => [
                'title' => 'Addresses'
            ],
            'incorporation_service_company' => [
                'title' => 'Incorporation service company'
            ],
            'bank_accounts' => [
                'title' => 'Bank accounts'
            ],
            'merchant_accounts' => [
                'title' => 'Merchant accounts'
            ],
            'tax_authorities' => [
                'title' => 'Tax authorities'
            ],
            'tax_returns_outstanding' => [
                'title' => 'Tax returns outstanding'
            ],
            'accounting_service_company' => [
                'title' => 'Accounting service company'
            ],
        ]
    ];

    public function getModel()
    {
        return new CorporateModel;
    }

}