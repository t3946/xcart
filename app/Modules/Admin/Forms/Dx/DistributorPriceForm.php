<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;

class DistributorPriceForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model'];

    public function getFieldsets()
    {
        return [[
            'd_product_catalog',
            'd_price_list',
            'd_currency',
            'cost_to_us_coef_x',
            'price_label',
            'd_map_policy',
            'd_map_prices',
            'new_map_price_coef_x',
            'supplier_products_price_multiplier',
        ]];
    }

    public function getFields()
    {
        $choices = [];

        foreach (CurrencyModel::objects() as $curr) {
            $choices[$curr->currency_id] = $curr->currency_code;
        }

        return [
            'd_product_catalog' => [
                'class' => CharField::class,
                'label' => 'Product catalog URL',
                'hint' => LanguageModel::translate('help_dx_catalog_url_text') ?? 'help_dx_catalog_url_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_price_list' => [
                'class' => CharField::class,
                'label' => 'Price-list URL',
                'hint' => LanguageModel::translate('help_dx_price_list_text') ?? 'help_dx_price_list_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_currency' => [
                'class' => DropDownField::class,
                'label' => 'Distributor currency',
                'hint' => LanguageModel::translate('help_dx_currency_text') ?? 'help_dx_currency_text',
                'choices' => $choices,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'cost_to_us_coef_x' => [
                'class' => CharField::class,
                'label' => 'Cost to us =',
                'hint' => LanguageModel::translate('help_dx_cost_to_us_text') ?? 'help_dx_cost_to_us_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
            ],
            'price_label' => [
                'class' => CharField::class,
                'label' => 'Price =',
                'hint' => LanguageModel::translate('help_dx_price_text') ?? 'help_dx_price_text',
                'html' => [
                    'style' => 'border: none;'
                ],
                'value' => 'calculated by our algorithm',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_map_policy' => [
                'class' => DropDownField::class,
                'label' => 'MAP policy',
                'hint' => LanguageModel::translate('help_dx_map_text') ?? 'help_dx_map_text',
                'choices' => [
                    '' => 'N/A',
                    'applies_to_selected_products' => 'applies to selected products',
                    'applies_to_all_products' => 'applies to all products',
                ],
                'html' => ['onchange' => "this.value ? $('#DistributorPriceForm_d_map_prices').closest('tr').show() : $('#DistributorPriceForm_d_map_prices').closest('tr').hide()"],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_map_prices' => [
                'class' => CharField::class,
                'label' => 'MAP prices URL',
                'hint' => LanguageModel::translate('help_dx_map_price_url_text') ?? 'help_dx_map_price_url_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => true
            ],
            'new_map_price_coef_x' => [
                'class' => CharField::class,
                'label' => 'MAP price =',
                'hint' => LanguageModel::translate('help_dx_map_price_text') ?? 'help_dx_map_price_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
            ],
            'supplier_products_price_multiplier' => [
                'class' => CharField::class,
                'label' => 'Distributor product price multiplier',
                'hint' => LanguageModel::translate('help_dx_price_multiplier_text') ?? 'help_dx_price_multiplier_text',
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],

        ];
    }
}