<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\TextAreaField;

class DistributorFrontEndMessagesForm extends DistributorForm
{
    public function getFieldsets()
    {
        return [[
            'cart_manufact_text_displayed',
            'lead_time_message',
        ]];
    }

    public function getFields()
    {
        return [
            'cart_manufact_text_displayed' => [
                'class' => TextAreaField::class,
                'label' => 'Front-end product page tabs',
                'hint' => LanguageModel::translate('help_dx_front_page_tabs_text') ?? 'help_dx_front_page_tabs_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['rows' => 5, 'cols' => 60]
            ],
            'lead_time_message' => [
                'class' => CharField::class,
                'hint' => LanguageModel::translate('help_dx_add_to_cart_popup_text') ?? 'help_dx_add_to_cart_popup_text',
                'label' => '"Add to cart" pop-up message',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
        ];
    }
}