<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\PaymentMethodModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\ModelForm;

class SitePaymentMethodForm extends ModelForm
{
    public array $exclude = [
        'position',
    ];

    public function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'logo' => [
                'class' => ImageField::class,
            ],
            'is_active' => [
                'class' => CheckboxField::class,
                'label' => 'Is Active',
            ],
        ];
    }

    public function getModel()
    {
        return new PaymentMethodModel();
    }

    public function getName()
    {
        return 'Edit Site Payment Method';
    }
}