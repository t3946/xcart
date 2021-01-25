<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\HiddenField;

class DistributorReturnPolicyForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'd_warranty_starts_when_order_is',
            'd_re_stocking_fee_for_authorized_returns',
            'd_re_stocking_fee_for_unauthorized_returns',
            'd_distributor_return_policy',
        ]];
    }

    public function getFields()
    {
        return [
            'd_warranty_starts_when_order_is' => [
                'class' => DropDownField::class,
                'label' => 'Warranty period starts when the order is',
                'choices' => [
                    'shipped' => 'shipped',
                    'received_by_customer' => 'received by the customer',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'extend' => 'd_warranty_last_day'
            ],
            'd_warranty_last_day' => [
                'class' => CharField::class,
                'label' => '',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => 'and lasts',
                'extend' => 'd_warranty_last_day_after',
            ],
            'd_warranty_last_day_after' => [
                'class' => HiddenField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => 'days',
            ],
            'd_re_stocking_fee_for_authorized_returns' => [
                'class' => CharField::class,
                'label' => 'Re-stocking fee for authorized returns',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extend' => 'd_re_stocking_fee_for_authorized_returns_after',
            ],
            'd_re_stocking_fee_for_authorized_returns_after' => [
                'class' => HiddenField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => '%',
            ],
            'd_re_stocking_fee_for_unauthorized_returns' => [
                'class' => CharField::class,
                'label' => 'Re-stocking fee for unauthorized returns',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extend' => 'd_re_stocking_fee_for_unauthorized_returns_after',
            ],
            'd_re_stocking_fee_for_unauthorized_returns_after' => [
                'class' => HiddenField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => '%',
            ],
            'd_distributor_return_policy' => [
                'class' => EditorField::class,
                'label' => 'Distributor return policy',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ]
        ];
    }
}