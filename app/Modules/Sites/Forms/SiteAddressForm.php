<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SitesAddressesModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class SiteAddressForm extends ModelForm
{
    public array $exclude = ['site', 'address'];

    public function getFields(): array
    {
        return [
            'address' => [
                'class' => DropDownField::class,
            ],
        ];
    }

    public function getModel(): SitesAddressesModel
    {
        return new SitesAddressesModel();
    }
}