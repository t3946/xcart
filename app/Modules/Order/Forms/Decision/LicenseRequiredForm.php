<?php

namespace Modules\Order\Forms\Decision;

use Modules\Core\Forms\FrontendForm;
use Xcart\App\Form\Fields\ImageField;

class LicenseRequiredForm extends FrontendForm
{
    const MAX_FILE_SIZE_MB = 10;

    public function getFields()
    {
        return [
            'license' => [
                'class' => ImageField::class,
                'maxSize' => self::MAX_FILE_SIZE_MB * 1024 * 1024,
                'types' => ['png', 'jpeg', 'jpg', 'pdf'],
                'required' => true,
            ],
        ];
    }
}
