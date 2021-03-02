<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Distributor\Models\DistributorContactUtilityModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorUtilityModel;
use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\UrlField;

class DistributorOrderSubmissionForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'd_our_dealer_account_n',
            'd_contact_name_for_templates',
        ]];
    }

    public function getFields()
    {
        /** @var DistributorModel $dx */
        $dx = $this->getInstance();
        return [
            'd_our_dealer_account_n' => [
                'class' => CharField::class,
                'label' => 'Our dealer account #',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_d_our_dealer_account_n_text'),
            ],
            'd_contact_name_for_templates' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'choices' => function () use ($dx): array {
                    foreach ($dx->contacts_model->filter(['email__isnt' => '']) as $contact) {
                        $result[$contact->id] = $contact->getEmail();
                    }
                    return $result ?? [];
                },
                'selected' => $dx->contacts_model->filter(['utility__utility_id' => DistributorUtilityModel::ORDER_MESSAGE_UTILITY])->valuesList('id', true),
                'label' => 'Order messages contact',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:100%;'],
                'hint' => LanguageModel::translate('help_d_contact_name_for_templates_text'),
                'required' => true
            ],
            'd_url_to_login_to_distributor_website' => [
                'class' => UrlField::class,
                'label' => 'URL to login to distributor website',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_d_url_to_login_to_distributor_website_text'),
                'extend' => 'Login URL',
            ],
            'd_login' => [
                'class' => CharField::class,
                'label' => 'Login/Username',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => true,
                'hint' => LanguageModel::translate('help_d_login_text'),
                'html' => ['class' => 'unhide']
            ],
            'd_password' => [
                'class' => CharField::class,
                'label' => 'Password',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => true,
                'hint' => LanguageModel::translate('help_d_password_text'),
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
                'hint' => LanguageModel::translate('help_submit_to_operator_text'),
                'html' => ['onchange' => "(this.value === 'by_email_or_and_fax')
                ? $('.by_email').closest('tr').show().closest('form').find('.by_site').closest('tr').hide()
                : $('.by_site').closest('tr').show().closest('form').find('.by_email').closest('tr').hide()"]
            ],
            'd_order_entry_operator_email' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'html' => ['class' => 'by_site'],
            ],
            'order_entry_template' => [
                'class' => DropDownField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'label' => 'Order entry template',
                'html' => ['class' => 'by_site', 'style' => 'width:400px;'],
                'choices' => static function () {
                    foreach (TemplateModel::customer_service() as $template) {
                        $category = $template->category;
                        $list[$template->id] = $category['name'] .' -> '. $template;
                    }
                    return $list ?? [];
                },
            ],
            'template_1_subj' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'value' => $dx->order_entry_template->subject_line,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'label' => 'Order entry message subject line',
                'html' => ['class' => 'by_site', 'readonly' => true, 'style' => 'border: none'],
            ],
            'template_1' => [
                'class' => EditorField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'value' => $dx->order_entry_template->message_body,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'label' => 'Order entry message body',
                'html' => ['class' => 'by_site'],
            ],
            'order_entry_special_instructions' => [
                'class' => EditorField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'by_email_or_and_fax',
                'label' => '<span style="color:red">Order entry special instructions</span>',
                'html' => ['class' => 'by_site'],
            ],
            'allow_dispatch_off_working_hours' => [
                'class' => CheckboxField::class,
                'label' => 'Allow dispatch off working hours',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_allow_dispatch_off_working_hours_text'),
                'html' => ['style' => 'width:50px;', 'class' => 'by_email'],
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',

            ],
            'add_cost_to_us_column_to_dispatch_message' => [
                'class' => CheckboxField::class,
                'label' => 'Add \'Cost to us\' column to dispatch message',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_add_cost_to_us_column_to_dispatch_message_text'),
                'html' => ['style' => 'width:50px;', 'class' => 'by_email'],
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
            ],
            'email' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'choices' => function () use ($dx): array {
                    foreach ($dx->contacts_model->filter(['email__isnt' => '']) as $contact) {
                        $result[$contact->id] = $contact->getEmail();
                    }
                    return $result ?? [];
                },
                'selected' => $dx->contacts_model->filter(['utility__utility_id' => DistributorUtilityModel::DISPATCH_UTILITY])->valuesList('id', true),
                'label' => "'Dispatch to' email contact",
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'hint' => LanguageModel::translate('help_dx_email_text'),
                'html' => ['class' => 'by_email', 'style' => 'width:100%;'],
            ],

            'order_submit_template' => [
                'class' => DropDownField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'label' => 'Dispatch template',
                'html' => ['class' => 'by_email', 'style' => 'width:400px;'],
                'choices' => static function () {
                    foreach (TemplateModel::distributors() as $template) {
                        $category = $template->category;
                        $list[$template->id] = $category['name'] .' -> '. $template;
                    }
                    return $list ?? [];
                },
            ],
            'template_2_subj' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'value' => $dx->order_submit_template->subject_line,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'label' => 'Dispatch subject line',
                'html' => ['class' => 'by_email', 'readonly' => true, 'style' => 'border: none'],
            ],
            'template_2' => [
                'class' => EditorField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'value' => $dx->order_submit_template->message_body,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'label' => 'Dispatch message body',
                'readonly' => true,
                'html' => ['class' => 'by_email'],
            ],
            'd_subject_line_8' => [
                'class' => CharField::class,
                'label' => "'Dispatch to' email subject line",
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'mess_body' => [
                'class' => EditorField::class,
                'label' => "'Dispatch to' email message",
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'html' => ['class' => 'by_email'],
            ],
            'order_submit_special_instructions' => [
                'class' => EditorField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx->submit_to_operator === 'through_distributor_website',
                'label' => '<span style="color:red">Dispatch special instructions</span>',
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
                'hint' => LanguageModel::translate('help_d_shipping_options_text'),
            ]
        ];
    }

    public function afterInstanceSave($instance)
    {
        if ($contacts = $this->email->getValue()) {
            foreach ($contacts as $contact_id) {
                if ($contact_id) {
                    DistributorContactUtilityModel::objects()->getOrCreate([
                        'contact_id' => $contact_id,
                        'utility_id' => DistributorUtilityModel::DISPATCH_UTILITY
                    ]);
                }
            }
            if ($contacts_to_delete = DistributorContactUtilityModel::objects()
                ->filter(['contact__manufacturerid' => $this->getDx()])
                ->exclude(['contact_id__in' => $contacts])
                ->valuesList(['contact_id'], true)) {
                DistributorContactUtilityModel::objects()->delete([
                    'contact_id__in' => $contacts_to_delete,
                    'utility_id' => DistributorUtilityModel::DISPATCH_UTILITY
                ]);
            }
        }

        if ($contacts = $this->d_contact_name_for_templates->getValue()) {
            foreach ($contacts as $contact_id) {
                if ($contact_id) {
                    DistributorContactUtilityModel::objects()->getOrCreate([
                        'contact_id' => $contact_id,
                        'utility_id' => DistributorUtilityModel::ORDER_MESSAGE_UTILITY
                    ]);
                }
            }
            if ($contacts_to_delete = DistributorContactUtilityModel::objects()
                ->filter(['contact__manufacturerid' => $this->getDx()])
                ->exclude(['contact_id__in' => $contacts])
                ->valuesList(['contact_id'], true)) {
                DistributorContactUtilityModel::objects()->delete([
                    'contact_id__in' => $contacts_to_delete,
                    'utility_id' => DistributorUtilityModel::ORDER_MESSAGE_UTILITY
                ]);
            }
        }
    }
}