<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Admin\Admin\DxTabsAdmin;
use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\RadioField;

class DistributorFrontEndMessagesForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces', 'taxes', 'feed_info'];

    public function getFieldsets()
    {
        return [[
            'tabs',
            'lead_time_message',
            'products_quantity_behavior',
            'allow_pre_orders',
        ]];
    }

    public function getFields()
    {
        return [
            'tabs' => [
                'class' => ListViewField::class,
                'adminClass' => DxTabsAdmin::class,
                'label' => 'Front-end product page tabs',
                'hint' => LanguageModel::translate('help_dx_front_page_tabs_text') ?? 'help_dx_front_page_tabs_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'lead_time_message' => [
                'class' => CharField::class,
                'hint' => LanguageModel::translate('help_dx_add_to_cart_popup_text') ?? 'help_dx_add_to_cart_popup_text',
                'label' => '"Add to cart" pop-up message',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'products_quantity_behavior' => [
                'class' => RadioField::class,
                'choices' => [
                    'N' => 'do NOT display quantity',
                    'R' => 'display real quantity',
                    'D' => 'display quantity of',
                ],
                'label' => 'Quantity in stock behavior on the SF product page',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'display_quantity_of',
                'html' => ['style' => 'width: 1em']
            ],
            'display_quantity_of' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width: 70px'],
                'extend' => 'display_quantity_of_hidden',
            ],
            'display_quantity_of_hidden' => [
                'class' => HiddenField::class,
                'extends' => 'if product is in stock',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
            ],
            'allow_pre_orders' => [
                'class' => CheckboxField::class,
                'label' => 'Allow to order ETA products',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width: 1em'],
                'hint' => LanguageModel::translate('help_dx_allow_pre_orders_text'),
            ],
            'calculate_shipping' => [
                'class' => CheckboxField::class,
                'label' => 'Show shipping cost on the product page',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width: 1em']
            ],
        ];
    }
}