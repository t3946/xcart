<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class SiteForm extends ModelForm
{
    public array $exclude = [
        'images',
        'config',
        'list_config',
        'prefix',
        'choices',
        'orderby',
        'static_page',
        'marketplaces',
        'short_name',
    ];

    public function getFields()
    {
        return [
            'corporates' => [
                'class' => Select2Field::class,
                'label' => 'Corporations',
                'multiple' => true,
                'html' => [
                    'class' => 'select2-field',
                ],
            ],
            'taxes' => [
                'class' => Select2Field::class,
                'label' => 'Taxes',
                'multiple' => true,
                'html' => [
                    'class' => 'select2-field',
                ],
            ],
            'payment_methods' => [
                'class' => Select2Field::class,
                'label' => 'Payment methods',
                'multiple' => true,
                'html' => [
                    'class' => 'select2-field',
                ],
            ],
        ];
    }

    public function getModel()
    {
        return new SiteModel();
    }

    public function getName()
    {
        return 'Edit Site';
    }
}