<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Editor\Fields\EditorField;
use Modules\Order\Models\AttentionTagModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\UrlField;

class DistributorOrderSubmissionForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'd_our_dealer_account_n',
            'd_contact_name_for_templates',
            'd_send_to_email_for_templates',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        return [
            'd_our_dealer_account_n' => [
                'class' => CharField::class,
                'label' => 'Our dealer account #',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_contact_name_for_templates' => [
                'class' => CharField::class,
                'label' => 'Contact name for templates',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_send_to_email_for_templates' => [
                'class' => CharField::class,
                'label' => '\'Send to\' email for templates',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_url_to_login_to_distributor_website' => [
                'class' => UrlField::class,
                'label' => 'URL to login to distributor website',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'extend' => 'Login URL',
            ],
            'd_login' => [
                'class' => CharField::class,
                'label' => 'Login/username',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => true,
                'html' => ['class' => 'unhide']
            ],
            'd_password' => [
                'class' => CharField::class,
                'label' => 'Password',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => true,
                'html' => ['class' => 'unhide']
            ],
            'submit_to_operator' => [
                'class' => DropDownField::class,
                'label' => 'Preferred way to submit orders is',
                'choices' => [
                    'through_distributor_website' => 'through distributor website',
                    'by_email_or_and_fax' => 'by email or/and fax',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['onchange' => "(this.value === 'by_email_or_and_fax')
                ? $('.by_email').closest('tr').show().closest('form').find('.by_site').closest('tr').hide()
                : $('.by_site').closest('tr').show().closest('form').find('.by_email').closest('tr').hide()"]
            ],
            'd_order_entry_operator_email' => [
                'class' => CharField::class,
                'label' => 'Order entry operator email',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'html' => ['class' => 'by_site'],
            ],
            'd_order_entry_operator_subject_line_8' => [
                'class' => CharField::class,
                'label' => 'Order entry operator subject line',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'html' => ['class' => 'by_site'],
            ],
            'd_instructions_to_order_entry_operator' => [
                'class' => EditorField::class,
                'label' => 'Instructions to order entry operator',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'html' => ['class' => 'by_site'],
            ],
            'allow_dispatch_off_working_hours' => [
                'class' => CheckboxField::class,
                'label' => 'Allow dispatch off working hours',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;', 'class' => 'by_email'],
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',

            ],
            'add_cost_to_us_column_to_dispatch_message' => [
                'class' => CheckboxField::class,
                'label' => 'Add \'Cost to us\' column to dispatch message',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;', 'class' => 'by_email'],
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
            ],
            'email' => [
                'class' => CharField::class,
                'label' => 'Distributor email',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'd_subject_line_8' => [
                'class' => CharField::class,
                'label' => 'Distributor subject line',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'mess_body' => [
                'class' => EditorField::class,
                'label' => 'Message to distributor',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'd_dispatch_instructions' => [
                'class' => EditorField::class,
                'label' => 'Dispatch instructions',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'd_shipping_options' => [
                'class' => CharField::class,
                'label' => 'Shipping options (use comma to separate)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ]
        ];
    }
}