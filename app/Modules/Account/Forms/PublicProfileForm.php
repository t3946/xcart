<?php

namespace Modules\Account\Forms;

use Modules\User\Models\UserAccount\UserModel;
use Modules\Core\Forms\FrontendModelForm;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\ImageField;

class PublicProfileForm extends FrontendModelForm
{
    public function getFields(): array
    {
        return [
            'public_name' => [
                'class' => CharCleanField::class,
            ],
            'location' => [
                'class' => CharCleanField::class,
            ],
            'avatar_image' => [
                'class' => ImageField::class,
                'maxSize' => 10 * 1024 * 1024,
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