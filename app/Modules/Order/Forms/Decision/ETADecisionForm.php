<?php

namespace Modules\Order\Forms\Decision;

use Modules\Core\Forms\FrontendForm;
use Xcart\App\Form\Fields\CharCleanField;

class ETADecisionForm extends FrontendForm
{
    public function getFields()
    {
        return [
            'comment' => [
                'class' => CharCleanField::class,
                'required' => false,
            ],

            'advice' => [
                'class' => CharCleanField::class,
                'required' => true,
            ],
        ];
    }
}