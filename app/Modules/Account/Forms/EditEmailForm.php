<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CharCleanField;

class EditEmailForm extends FrontendModelForm
{
    public function getFields(): array
    {
        return [
            'email' => [
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
