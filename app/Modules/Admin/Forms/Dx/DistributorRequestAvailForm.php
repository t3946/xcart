<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorContactUtilityModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorUtilityModel;
use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\Select2Field;

class DistributorRequestAvailForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces', 'taxes'];

    public function getFieldsets()
    {
        return [[
            'd_availability_must_be_checked',
            'request_avail_emails',
            'request_avail_template',
            'd_message_body_14'
        ]];
    }

    public function getFields()
    {
        /** @var DistributorModel $dx */
        $dx = $this->getInstance();

        return [
            'd_availability_must_be_checked' => [
                'class' => CheckboxField::class,
                'label' => 'Availability must be checked before order is dispatched for fulfillment',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_d_availability_must_be_checked_text'),
                'html' => ['style' => 'width:50px;', 'onchange' => "this.checked ? $('.click_hide').closest('tr').show() : $('.click_hide').closest('tr').hide()"],
            ],
            'request_avail_emails' => [
                'class' => Select2Field::class,
                'choices' => function () use ($dx): array {
                    foreach ($dx->contacts_model->filter(['email__isnt' => '']) as $contact) {
                        $result[$contact->id] = $contact->getEmail();
                    }
                    return $result ?? [];
                },
                'selected' => $dx->contacts_model->filter(['utility__utility_id' => DistributorUtilityModel::REQUEST_AVAIL_UTILITY])->valuesList(['id'], true),
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => [
                    'class' => 'click_hide select2-field',
                    'style' => 'width:100%;',
                ],
                'multiple' => true,
                'label' => 'Availability request contact',
                'hint' => LanguageModel::translate('help_dx_request_avail_emails_text'),
            ],
            'request_avail_template' => [
                'class' => DropDownField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'label' => 'Availability request template',
                'html' => ['class' => 'click_hide', 'style' => 'width:100%;'],
                'choices' => static function () {
                    foreach (TemplateModel::distributors() as $template) {
                        $category = $template->category;
                        $list[$template->id] = $category['name'] .' -> '. $template;
                    }
                    return $list ?? [];
                },
                'hint' => LanguageModel::translate('help_dx_request_avail_template_text'),
            ],
            'template_1_subj' => [
                'class' => CharField::class,
                'value' => $dx->request_avail_template->subject_line,
                'label' => 'Availability request subject line',
                'html' => ['class' => 'click_hide', 'readonly' => true, 'style' => 'border: none'],
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'hint' => LanguageModel::translate('help_dx_template_1_subj_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'template_1' => [
                'class' => EditorField::class,
                'value' => $dx->request_avail_template->message_body,
                'label' => 'Availability request message body',
                'html' => ['class' => 'click_hide tinymce-field'],
                'readonly' => true,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'hint' => LanguageModel::translate('help_dx_template_1_text'),
                'hintTemplate' => $this->hintTemplate,
            ],

            'd_server_min_distributor_time' => [
                'class' => CharField::class,
                'label' => 'Server time - Distributor time',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:100px;'],
            ],
            'd_availability_request_schedule' => [
                'class' => CharField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'label' => 'Availability request schedule',
                'html' => ['style' => 'display:none;'],
                'hint' => LanguageModel::translate('help_dx_availability_request_schedule_text'),
            ],
        ];
    }

    public function afterInstanceSave($instance)
    {
        if ($contacts = $this->request_avail_emails->getValue()) {
            foreach ($contacts as $contact_id) {
                if ($contact_id) {
                    DistributorContactUtilityModel::objects()->getOrCreate([
                        'contact_id' => $contact_id,
                        'utility_id' => DistributorUtilityModel::REQUEST_AVAIL_UTILITY
                    ]);
                }
            }
            if ($contacts_to_delete = DistributorContactUtilityModel::objects()
                ->filter(['contact__manufacturerid' => $this->getDx()])
                ->exclude(['contact_id__in' => $contacts])
                ->valuesList(['contact_id'], true)) {
                DistributorContactUtilityModel::objects()->delete([
                    'contact_id__in' => $contacts_to_delete,
                    'utility_id' => DistributorUtilityModel::REQUEST_AVAIL_UTILITY
                ]);
            }
        }
    }
}