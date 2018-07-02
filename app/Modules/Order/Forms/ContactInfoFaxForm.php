<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 29.05.2018
 * Time: 10:37
 */

namespace Modules\Order\Forms;


use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharField;

class ContactInfoFaxForm extends ContactInfoForm
{
    public function getFields(): array
    {
        $fields = parent::getFields();

        $fields['fax'] = [
            'class' => CharField::class,
            'label' => 'Fax <span class="comment">(optional)</span>',
            'required' => false,
            'validators' => [
                new PhoneValidator(),
            ],
            'html' => [
                'placeholder' => '(609) 924-8399',
                'class' => 'phone'
            ],
        ];

        return $fields;
    }
}