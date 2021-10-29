<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\AddressModel;
use Modules\Sites\Models\SitesAddressesModel;
use Modules\Sites\Models\SocialModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class SiteAddressForm extends ModelForm
{
    public array $exclude = ['site'];

    public function getFields(): array
    {
        return [
            'address' => [
                'class' => Select2Field::class,
                'choices' => static function () {
                    /** @var AddressModel $social_model */
                    foreach (AddressModel::objects()->all() as $address_model) {
                        $items[$address_model->pk] = (string)$address_model;
                    }
                    return $items ?? [];
                }
            ],
        ];
    }

    public function getModel(): SitesAddressesModel
    {
        return new SitesAddressesModel();
    }
}