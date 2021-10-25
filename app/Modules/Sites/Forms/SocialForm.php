<?php

namespace Modules\Sites\Forms;

use Modules\Core\Models\CountryModel;
use Modules\Sites\Models\SocialModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class SocialForm extends ModelForm
{
    public function getFields(): array
    {
        return [
            'type' => [
                'class' => CharField::class,
                'label' => 'Type social networks',
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'logo_path' => [
                'class' => ImageField::class,
            ],
            'url' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ]
        ];
    }

    public function getModel(): SocialModel
    {
        return new SocialModel();
    }
}