<?php

namespace Modules\Order\Forms;


class AccountsPayableForm extends ContactInfoFaxForm
{
    public $replacement = [
        'firstname' => 'accounts_payable_full_name',
        'phone' => 'accounts_payable_phone',
        'phone_ext' => 'accounts_payable_phone_ext',
        'email' => 'accounts_payable_email',
        'fax' => 'accounts_payable_fax',
    ];

    public function getFields(): array
    {
        $fields = parent::getFields();

        $fields['firstname']['hint'] = 'Full name of the person who will remit the payment';
        $fields['phone']['hint'] = 'Phone # of the person who will remit the payment';
        $fields['fax']['hint'] = 'Fax number of the person who will remit the payment';
        $fields['email']['hint'] = 'Email of the person who will remit the payment';

        return $fields;
    }
}