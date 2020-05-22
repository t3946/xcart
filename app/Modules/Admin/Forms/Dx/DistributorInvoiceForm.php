<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;

class DistributorInvoiceForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model'];

    public function getFieldsets()
    {
        return [[
            'd_invoices_sent_by_email_to',
            'd_invoices_sent_by_fax_to',
            'd_invoices_mailed_to_our_checkbox',
        ]];
    }

    public function getFields()
    {
        return [
            'd_invoices_sent_by_email_to' => [
                'class' => CheckboxField::class,
                'label' => ' ',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'd_invoices_sent_to',
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'html' => ['style' => 'width: 1em']
            ],
            'd_invoices_sent_to' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => 'sent by email to',
                'html' => ['style' => 'width: 350px']
            ],
            'd_invoices_sent_by_fax_to' => [
                'class' => CheckboxField::class,
                'label' => 'Distributor invoices are',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'd_invoices_by_fax_sent_to',
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'html' => ['style' => 'width: 1em']
            ],
            'd_invoices_by_fax_sent_to' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'extends' => 'sent by fax to',
                'html' => ['style' => 'width: 350px']
            ],
            'd_invoices_mailed_to_our_checkbox' => [
                'class' => CheckboxField::class,
                'label' => ' ',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'inputTemplate' => 'admin/distributor/form/checkbox.tpl',
                'extend' => 'd_invoices_mailed_to_our',
                'html' => ['style' => 'width: 1em']
            ],
            'd_invoices_mailed_to_our' => [
                'class' => DropDownField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'choices' => [
                    'usa' => 'US address',
                    'canada' => 'Canadian address',
                ],
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'extends' => 'mailed to our',
                'html' => ['style' => 'width: 200px']
            ],
        ];
    }
}