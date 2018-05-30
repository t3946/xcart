<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 29.05.2018
 * Time: 11:02
 */

namespace Modules\Order\Forms;


class PurchasingManagerForm extends ContactInfoFaxForm
{
    public $replacement = [
        'firstname' => 'name_of_purchaser',
        'phone' => 'purchase_manager_phone',
        'phone_ext' => 'purchase_manager_phone_ext',
        'email' => 'purchase_manager_email',
        'fax' => 'purchase_manager_fax',
    ];

    public function getFields(): array
    {
        $fields = parent::getFields();

        $fields['firstname']['hint'] = 'Full name of the person placing the order';
        $fields['phone']['hint'] = 'Phone number of the person placing the order';
        $fields['fax']['hint'] = 'Fax number of the person placing the order';
        $fields['email']['hint'] = 'Email of the person placing the order';

        return $fields;
    }
}