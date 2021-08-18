<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\ImageField;

class EditNameForm extends FrontendModelForm
{
    public function getFields(): array
    {
        return [
            'name' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
            'location' => [
                'class' => CharCleanField::class,
            ],
            'avatar_image' => [
                'class' => ImageField::class,
                'maxSize' => 100 * 1024,
                'types' => ['png', 'jpeg', 'jpg'],
                'required' => false,
            ],
        ];
    }

    public function getModel()
    {
        return new UserModel();
    }
}