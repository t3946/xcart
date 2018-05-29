<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 29.05.2018
 * Time: 11:03
 */

namespace Modules\Order\Forms;


class AccountsPayableForm extends ContactInfoFaxForm
{
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