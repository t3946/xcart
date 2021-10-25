<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\AddressModel;
use Xcart\App\Form\ModelForm;

class AddressForm extends ModelForm
{

    public function getModel(): AddressModel
    {
        return new AddressModel();
    }
}