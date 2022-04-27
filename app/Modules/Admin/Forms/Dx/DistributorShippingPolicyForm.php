<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\LanguageModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;

class DistributorShippingPolicyForm extends DistributorForm
{
    public array $exclude = ['provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces', 'taxes', 'feed_info'];

    public function getFieldsets()
    {
        return [
            [
                'd_ships_to_within',
                'carriers',
                'dx_leadtime',
                'amazon_leadtime_to_ship',
                'amazon_leadtime_for_fba_loads',
            ],
            [
                'distributor_offers_free_shipping',
            ],
            [
                'warehouse_pickups_are_allowed',
                'd_drop_ship_fee_select',
                'd_drop_ship_fee_in_us',
                'd_minimum_order_amount',
                'd_minimum_order_amount_in_us',
                'd_for_orders_below_min_order_amount',
                'd_dealer_discount_reduced_from',
            ],
            [
                'update_approximation_shipping_rates',
                'shipping_last_update_date'
            ]
        ];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        $currency = $dx->currency;

        $countriesSelected = (function () {
            $opts = array_map('trim', explode(',', $this->getInstance()->d_ships_to_within));
            foreach ($opts as $opt) {
                $result[$opt] = $opt;
            }
            return $result ?? [];
        })->__invoke();

        $countries = (static function () {
            $opts = CountryModel::objects()->order(['name']);
            $result = [
                'All regions' => 'All regions',
                'North America' => 'North America',
                'Europe' => 'Europe',
                'Australia and Oceania' => 'Australia and Oceania',
                'Latin America' => 'Latin America',
                'Former USSR' => 'Former USSR',
                'Asia' => 'Asia',
                'Africa' => 'Africa',
                'Antarctica' => 'Antarctica',
            ];

            foreach ($opts as $opt) {
                $result[$opt->name] = $opt->name;
            }
            return $result ?? [];
        })->__invoke();

        return [
            'd_ships_to_within' => [
                'class' => Select2Field::class,
                'label' => 'Distributor ships to/within',
                'choices' => $countries,
                'selected' => $countriesSelected,
                'html' => [
                    'style' => 'width:400px;',
                    'data-placeholder' => 'Click to select shipping zone',
                ],
                'multiple' => true,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_ships_to_text') ?? 'help_dx_ships_to_text',
            ],
            'carriers' => [
                'class' => Select2Field::class,
                'label' => 'Shipping carriers used by distributor',
                'html' => [
                    'style' => 'width:400px;',
                    'data-placeholder' => 'Click to select shipping carriers',
                ],
                'multiple' => true,
                'choices' => static function () {
                    foreach (TrackingLinksCarrierModel::objects()->order(['orderby']) as $carrier) {
                        $result[$carrier->pk] = (string)$carrier;
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_shipping_methods_text') ?? 'help_dx_shipping_methods_text',
            ],
            'dx_leadtime' => [
                'class' => CharField::class,
                'label' => 'Dx to Cx lead time [business days]',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => 'from',
                'extend' => 'dx_leadtime_to',
                'hint' => LanguageModel::translate('help_dx_to_cx_lead_text') ?? 'help_dx_to_cx_lead_text',
            ],
            'dx_leadtime_to' => [
                'class' => CharField::class,
                'label' => '',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => 'to',
            ],
            'amazon_leadtime_to_ship' => [
                'class' => CharField::class,
                'label' => 'Amazon to Cx lead time to ship for MFN orders [business days]',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
                'hint' => LanguageModel::translate('help_amazon_to_cx_lead_text') ?? 'help_amazon_to_cx_lead_text',
            ],
            'amazon_leadtime_for_fba_loads' => [
                'class' => CharField::class,
                'label' => 'Dx to Amazon lead time (DLT) for FBA loads [days]',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
                'hint' => LanguageModel::translate('help_dx_to_amazon_lead_text') ?? 'help_dx_to_amazon_lead_text',
            ],
            'distributor_offers_free_shipping' => [
                'class' => DropDownField::class,
                'label' => 'Distributor offers free shipping',
                'choices' => [
                    'never' => 'never',
                    'on_orders_over' => 'on orders over',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'extend' => 'free_shipping_on_orders_over_value',
                'hint' => LanguageModel::translate('help_dx_offers_free_text') ?? 'help_dx_offers_free_text',
            ],
            'free_shipping_on_orders_over_value' => [
                'class' => CharField::class,
                'label' => '',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:110px;'],
                'extends' => "{$currency->symbol_prefix}{$currency}",
            ],
            'warehouse_pickups_are_allowed' => [
                'class' => DropDownField::class,
                'label' => 'Warehouse pickups are allowed?',
                'choices' => [
                    'N' => 'No',
                    'Y' => 'Yes',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_warehouse_pickups_text') ?? 'help_dx_warehouse_pickups_text',
            ],
            'd_drop_ship_fee_select' => [
                'class' => DropDownField::class,
                'label' => 'Drop-ship fee',
                'choices' => [
                    '' => 'N/A',
                    'applies_to_all_orders' => 'applies to all orders',
                    'applies_to_orders_below_minimum_order_amount_only' => 'applies to orders below minimum order amount only',
                ],
                'html' => ['onchange' => "this.value ? $('#DistributorShippingPolicyForm_d_drop_ship_fee_in_us').closest('tr').show() : $('#DistributorShippingPolicyForm_d_drop_ship_fee_in_us').closest('tr').hide()"],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_dropship_fee_text'),
            ],
            'd_drop_ship_fee_in_us' => [
                'class' => CharField::class,
                'label' => "Drop-ship fee amount",
                'html' => ['style' => 'width:50px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'd_drop_ship_fee_type',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'hidden' => empty($dx->d_drop_ship_fee_select),
                'hint' => LanguageModel::translate('help_dx_dropship_fee_price_text'),
            ],
            'd_drop_ship_fee_type' => [
                'class' => DropDownField::class,
                'choices' => [
                    'value' => $currency->symbol_prefix.$currency->symbol,
                    'percent' => '% of subtotal in Cost to us pricing',
                    'percent_total' => '% of total order amount',
                ]
            ],
            'd_minimum_order_amount' => [
                'class' => DropDownField::class,
                'label' => 'Minimum order amount',
                'choices' => [
                    '' => 'N/A',
                    'applies_to_all_orders' => 'applies to all orders',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_minimum_order_amount_text'),
                'html' => ['onchange' => "this.value ? $('#DistributorShippingPolicyForm_d_minimum_order_amount_in_us, #DistributorShippingPolicyForm_d_for_orders_below_min_order_amount').closest('tr').show() : $('#DistributorShippingPolicyForm_d_minimum_order_amount_in_us, #DistributorShippingPolicyForm_d_for_orders_below_min_order_amount').closest('tr').hide()"],
            ],
            'd_minimum_order_amount_in_us' => [
                'class' => CharField::class,
                'label' => "Minimum order amount in {$currency->symbol_prefix}{$currency}",
                'html' => ['style' => 'width:50px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => empty($dx->d_minimum_order_amount),
                'hint' => LanguageModel::translate('help_dx_minimum_order_amount_price_text'),
            ],
            'd_for_orders_below_min_order_amount' => [
                'class' => DropDownField::class,
                'label' => '(For) orders below minimum order amount',
                'choices' => [
                    'are_rejected' => 'are rejected',
                    'drop_ship_fee_is_applied' => 'drop-ship fee is applied',
                    'dealer_discount_is_reduced' => 'dealer discount is reduced',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => empty($dx->d_minimum_order_amount),
                'hint' => LanguageModel::translate('help_dx_below_minimum_order_text') ?? 'help_dx_below_minimum_order_text',
                'html' => ['onchange' => "this.value !== 'dealer_discount_is_reduced' ? $('#DistributorShippingPolicyForm_d_dealer_discount_reduced_from, #DistributorShippingPolicyForm_d_dealer_discount_reduced_to').closest('tr').hide() : $('#DistributorShippingPolicyForm_d_dealer_discount_reduced_from, #DistributorShippingPolicyForm_d_dealer_discount_reduced_to').closest('tr').show()"]
            ],
            'd_dealer_discount_reduced_from' => [
                'label' => ' ',
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->d_for_orders_below_min_order_amount !== 'dealer_discount_is_reduced',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => '<b>from</b> <a title="'.LanguageModel::translate('help_dx_d_dealer_discount_reduced_from_text').'" class="tooltip"><i class="fa fa-question-circle pointer"></i></a>',
                'extend' => 'd_dealer_discount_reduced_to',
            ],
            'd_dealer_discount_reduced_to' => [
                'label' => ' ',
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->d_for_orders_below_min_order_amount !== 'dealer_discount_is_reduced',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => '<b>to</b> <a title="'.LanguageModel::translate('help_dx_d_dealer_discount_reduced_to_text').'" class="tooltip"><i class="fa fa-question-circle pointer"></i></a>',
            ],
            'update_approximation_shipping_rates' => [
                'class' => CheckboxField::class,
                'label' => 'Force ASR (approximate shipping rates) update',
                'hint' => LanguageModel::translate('help_dx_update_approximate_shipping_rates_text') ?? 'help_dx_update_approximate_shipping_rates_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:16px;'],
            ],
            'shipping_last_update_date' => [
                'class' => DateField::class,
                'label' => 'Date and time of the last ASR update',
                'hint' => LanguageModel::translate('help_dx_date_approximate_shippings_text') ?? 'help_dx_date_approximate_shippings_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:150px; border: none; background: white; color: black', 'disabled' => 'disabled'],
            ]
        ];
    }

    public function beforeInstanceSave($instance)
    {
        parent::beforeInstanceSave($instance);
        if ($instance->d_ships_to_within && is_array($instance->d_ships_to_within)) {
            $instance->d_ships_to_within = implode(',', $instance->d_ships_to_within);
        }
    }
}