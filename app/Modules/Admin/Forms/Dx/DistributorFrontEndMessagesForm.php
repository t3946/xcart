<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\TextAreaField;

class DistributorFrontEndMessagesForm extends DistributorForm
{

    public function getFields()
    {
        return [
            'cart_manufact_text_displayed' => [
                'class' => TextAreaField::class,
                'label' => 'Front-end product page tabs',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['rows' => 5, 'cols' => 60]
            ],
            'lead_time_message' => [
                'class' => CharField::class,
                'hint' => 'Hint',
                'label' => '"Add to cart" pop-up message',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
        ];
    }
}