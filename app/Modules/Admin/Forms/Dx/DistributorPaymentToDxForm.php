<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\LanguageModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\Select2Field;

class DistributorPaymentToDxForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [
            'Payment to distributor arrangements' => [
                'd_we_pay_to_distributor_by',
                'd_pay_to_distributor_by',
                'd_net_payment_terms_in_days',
            ],
            'Distributor checking account details' => [
                'dcad_company_name',
                'dcad_address_caption',
                'dcad_address',
                'dcad_address_2',
                'dcad_city',
                'dcad_country',
                'dcad_state',
                'dcad_zipcode',
                'dcad_bank_name',
                'dcad_swift',
                'dcad_routing_number',
                'dcad_account_number',
            ],
            'Reconciliation settings' => [
                'd_bulk_or_individual_order_payments',
                'distributor_charges_for_each_order_twice_and_split_invoices',
                'd_search_keyphrase_for_reconciliation',
                'hint' => [LanguageModel::translate('help_dx_reconciliation_settings') ?? 'help_dx_reconciliation_settings'],
            ]
        ];
    }

    public function getFields()
    {
        $phrases = (function () {
            $opts = array_map('trim', explode('<OR>', $this->getInstance()->d_search_keyphrase_for_reconciliation));
            foreach ($opts as $opt) {
                $result[$opt] = $opt;
            }
            return $result ?? [];
        })->__invoke();

        return [
            'd_we_pay_to_distributor_by' => [
                'class' => DropDownField::class,
                'label' => 'We pay to distributor by',
                'choices' => [
                    'credit_card' => 'credit card',
                    'paypal_balance' => 'PayPal balance',
                    'check' => 'check',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_we_pay_to_distributor_by') ?? 'help_dx_we_pay_to_distributor_by',
            ],
            'd_pay_to_distributor_by' => [
                'class' => DropDownField::class,
                'label' => 'If we pay to distributor by',
                'choices' => [
                    '' => '',
                    'check' => 'check',
                    'EFT' => 'electronic funds transfer (EFT)',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'extend' => 'd_pay_to_distributor_save_text',
                'hint' => LanguageModel::translate('help_dx_if_we_pay_to_distributor') ?? 'help_dx_if_we_pay_to_distributor',
            ],
            'd_pay_to_distributor_save_text' => [
                'class' => DropDownField::class,
                'choices' => [
                    '' => "we didn't check if we can save",
                    'we_can_save' => 'we can save',
                    'we_cannot_save' => "we can't save",
                ],
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'extend' => 'd_we_can_save',
                'extends' => ', ',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_we_can_save' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extend' => 'd_we_can_save_after',
            ],
            'd_we_can_save_after' => [
                'class' => HiddenField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => '%',
            ],
            'd_net_payment_terms_in_days' => [
                'class' => CharField::class,
                'label' => 'NET payment terms in days (put 0 if N/A)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:80px;'],
                'extends' => 'NET',
                'hint' => LanguageModel::translate('help_dx_net_payment_terms_in_days') ?? 'help_dx_net_payment_terms_in_days',
            ],
            'dcad_company_name' => [
                'class' => CharField::class,
                'label' => 'Company name',
                'html' => ['style' => 'width:300px'],
                'hint' => LanguageModel::translate('help_dx_dcad_company_name') ?? 'help_dx_dcad_company_name',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_address_caption' => [
                'class' => CharField::class,
                'label' => 'Company address',
                'html' => ['style' => 'width:200px; border: none'],
                'hint' => LanguageModel::translate('help_dx_dcad_address') ?? 'help_dx_dcad_address',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'html' => ['style' => 'width:300px'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
                'html' => ['style' => 'width:300px'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_city' => [
                'class' => CharField::class,
                'label' => 'City',
                'html' => ['style' => 'width:300px'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'html' => ['style' => 'width:300px'],
                'choices' => static function () {
                    foreach (CountryModel::objects() as $country) {
                        $result[$country->code] = (string)$country;
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:300px;'],
                'choices' => static function () {
                    foreach (StateModel::objects()->filter(['country_code__in' => ['US']]) as $state) {
                        $result[$state->code] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'html' => ['style' => 'width:300px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,

            ],
            'dcad_bank_name' => [
                'class' => CharField::class,
                'label' => 'Bank name',
                'html' => ['style' => 'width:300px'],
                'hint' => LanguageModel::translate('help_dx_dcad_bank_name') ?? 'help_dx_dcad_bank_name',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_swift' => [
                'class' => CharField::class,
                'label' => 'Swift / BIC',
                'html' => ['style' => 'width:300px'],
                'hint' => LanguageModel::translate('help_dx_dcad_swift') ?? 'help_dx_dcad_swift',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_routing_number' => [
                'class' => CharField::class,
                'label' => 'Routing number',
                'html' => ['style' => 'width:300px'],
                'hint' => LanguageModel::translate('help_dx_dcad_routing_number') ?? 'help_dx_dcad_routing_number',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dcad_account_number' => [
                'class' => CharField::class,
                'label' => 'Account number / IBAN',
                'html' => ['style' => 'width:300px'],
                'hint' => LanguageModel::translate('help_dx_dcad_account_number') ?? 'help_dx_dcad_account_number',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_bulk_or_individual_order_payments' => [
                'class' => DropDownField::class,
                'label' => 'Bulk or individual order payments',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'choices' => [
                    'distributor_charges_for_each_order_separately' => "distributor charges for each order separately",
                    'distributor_may_charge_for_several_orders_at_once' => 'distributor may charge for several orders at once',
                    'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping' => 'distributor charges for each order twice: one charge for products and one charge for shipping',
                ],
                'html' => ['onchange' => "this.value === 'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping' 
                ? $('[id$=distributor_charges_for_each_order_twice_and_split_invoices]').closest('tr').show() 
                : $('[id$=distributor_charges_for_each_order_twice_and_split_invoices]').closest('tr').hide()"],
                'hint' => LanguageModel::translate('help_dx_d_bulk_or_individual_order_payments') ?? 'help_dx_d_bulk_or_individual_order_payments',
            ],
            'distributor_charges_for_each_order_twice_and_split_invoices' => [
                'class' => CheckboxField::class,
                'label' => 'Split invoices (by Cost + Tax and Shipping)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $this->getInstance()->d_bulk_or_individual_order_payments !== 'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping',
            ],
            'd_search_keyphrase_for_reconciliation' => [
                'class' => Select2Field::class,
                'label' => 'Search keyphrase for reconciliation',
                'choices' => $phrases,
                'selected' => $phrases,
                'html' => ['style' => 'width:100%'],
                'editable' => true,
                'multiple' => true,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_d_search_keyphrase_for_reconciliation') ?? 'help_dx_d_search_keyphrase_for_reconciliation',
            ],
        ];
    }

    public function beforeInstanceSave($instance)
    {
        parent::beforeInstanceSave($instance);
        if ($instance->d_search_keyphrase_for_reconciliation && is_array($instance->d_search_keyphrase_for_reconciliation)) {
            $instance->d_search_keyphrase_for_reconciliation = implode('<OR>', $instance->d_search_keyphrase_for_reconciliation);
        }
    }
}