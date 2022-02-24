<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CharCleanField;

class EditPhoneForm extends FrontendModelForm
{
    public function getFields(): array
    {
        return [
            'phone' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
            'phone_country_code' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
        ];
    }

    public function getModel()
    {
        return new UserModel();
    }
}
