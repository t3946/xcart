<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\UrlField;

class DistributorOrderTrackingForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces', 'taxes', 'feed_info'];

    public function getFieldsets()
    {
        return [[
            'd_available_on_distributor_site_checkbox',
            'd_sent_by_email_to',
            'd_put_on_the_invoices',
        ]];
    }

    public function getFields()
    {
        return [
            'd_available_on_distributor_site_checkbox' => [
                'class' => CheckboxField::class,
                'label' => ' ',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'd_available_on_distributor_site_url',
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'html' => ['style' => 'width: 1em']
            ],
            'd_available_on_distributor_site_url' => [
                'class' => UrlField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/url.tpl',
                'extends' => 'available on distributor website',
                'html' => ['style' => 'width: 300px']
            ],
            'd_sent_by_email_to' => [
                'class' => CheckboxField::class,
                'label' => 'Tracking number is',
                'hint' => LanguageModel::translate('help_dx_d_sent_by_email_to_text') ?? 'help_dx_d_sent_by_email_to_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'd_sent_by_email_to_email_address',
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'html' => ['style' => 'width: 1em']
            ],
            'd_sent_by_email_to_email_address' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => ' sent by email to',
                'html' => ['style' => 'width: 350px']
            ],
            'd_put_on_the_invoices' => [
                'class' => CheckboxField::class,
                'label' => ' ',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'extend' => 'd_put_on_the_invoices_hidden',
                'html' => ['style' => 'width: 1em']
            ],
            'd_put_on_the_invoices_hidden' => [
                'class' => HiddenField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => '  put on the invoice',
                'html' => ['style' => 'width: 350px']
            ],
        ];
    }
}