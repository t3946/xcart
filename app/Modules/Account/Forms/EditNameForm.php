<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CharCleanField;

class EditNameForm extends FrontendModelForm
{
    public function getFields(): array
    {
        return [
            'name' => [
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