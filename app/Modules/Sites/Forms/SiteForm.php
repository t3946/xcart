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
                'multiple' => true,
                'label' => 'Corporations',
            ],
            'taxes' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'label' => 'Taxes',
            ],
            'payment_methods' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'label' => 'Payment methods',
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