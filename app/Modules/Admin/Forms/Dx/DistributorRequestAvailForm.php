<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorModel;
use Modules\Editor\Fields\EditorField;
use Modules\Forms\Helpers\SnippetHelper;
use Modules\Forms\Models\SnippetModel;
use Modules\Forms\Models\TemplateModel;
use Modules\Order\Models\AttentionTagModel;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class DistributorRequestAvailForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'd_availability_must_be_checked',
            'd_send_to_email_14',
            'request_avail_template',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();

        $selected_emails = (function () {
            $opts = array_map('trim', explode(',', $this->getInstance()->d_send_to_email_14));
            foreach ($opts as $opt) {
                $result[$opt] = $opt;
            }
            return $result ?? [];
        })->__invoke();

        $email_contacts = (function () {
            $opts = $this->getInstance()->contacts_model->order(['position']);
            foreach ($opts as $opt) {
                $result[$opt->email] = $opt->email;
            }
            return $result ?? [];
        })->__invoke();

        return [
            'd_availability_must_be_checked' => [
                'class' => CheckboxField::class,
                'label' => 'Availability must be checked before order is dispatched for fulfillment',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;', 'onchange' => "this.checked ? $('.click_hide').closest('tr').show() : $('.click_hide').closest('tr').hide()"],
            ],
            'd_send_to_email_14' => [
                'class' => Select2Field::class,
                'selected' => $selected_emails,
                'choices' => $email_contacts,
                'multiple' => true,
                'label' => "'Send to' email",
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide', 'style' => 'width:400px;'],
                'required' => true
            ],

            'd_email_subject_14' => [
                'class' => CharField::class,
                'label' => 'Subject line',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide']
            ],
            'd_message_body_14' => [
                'class' => EditorField::class,
                'label' => 'Message body',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide']
            ],
            'add_ca_status_id' => [
                'class' => DropDownField::class,
                'label' => ' ',
                'extends' => 'Add the following Attention tag',
                'choices' => static function () {
                    $res[] = 'add nothing';
                    foreach (AttentionTagModel::objects()->order(['status']) as $tag) {
                        $res[$tag->pk] = $tag->status;
                    }
                    return $res ?? [];
                },
                'inputTemplate' => 'admin/distributor/form/dropdown.tpl',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide']
            ],
            'request_avail_template' => [
                'class' => DropDownField::class,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'label' => 'Request  availability template',
                'html' => ['class' => 'click_hide', 'style' => 'width:400px;'],
                'choices' => static function () {
                    foreach (TemplateModel::distributors() as $template) {
                        $result[$template->id] = (string)$template;
                    }
                    return $result ?? [];
                }
            ],
            'd_sec14_show_header' => [
                'class' => CheckboxField::class,
                'label' => 'Show header',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_sec14_show_items_stock' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{items-stock}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_sec14_show_shipto' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{shipto}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_sec14_show_items_cost' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{items-cost}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_sec14_show_footer' => [
                'class' => CheckboxField::class,
                'label' => 'Show footer',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_server_min_distributor_time' => [
                'class' => CharField::class,
                'label' => 'Server time - Distributor time',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:100px;'],
            ],
        ];
    }

    protected function populateFromInstance(Model $model)
    {
        /** @var DistributorModel $model */
        parent::populateFromInstance($model);
        $snippets = [];
        $fields = $this->getFieldsInit();
        /** @var TemplateModel $template */
        if ($template = TemplateModel::objects()->get(['pk' => TemplateModel::REQUEST_AVAILABILITY_TEMPLATE_ID])) {
            foreach (SnippetModel::objects()->filter(['code__in' => ['distributorcontactname', 'signature', 'userfullname', 'userfirstname']]) as $snippet) {
                $to_render = [
                    'user' => new UserModel(['firstname' => 'Amy']),
                    'distributor' => $model,
                    'site' => $model->sites->limit(1)->get() ?: Xcart::app()->getModule('Sites')->getSite()
                ];
                if ($model->getContactNameForTemplates() || $snippet->code !== 'distributorcontactname') {
                    $snippets["{{{$snippet->code}}}"] = $snippet->render($to_render);
                }
            }

            if (!$fields['d_message_body_14']->getValue()) {
                $fields['d_message_body_14']->setValue(SnippetHelper::renderSnippets($template->message_body, $snippets));
            }

            if (!$fields['d_email_subject_14']->getValue()) {
                $fields['d_email_subject_14']->setValue(SnippetHelper::renderSnippets($template->subject_line, []));
            }
        }
    }

    public function beforeInstanceSave($instance)
    {
        parent::beforeInstanceSave($instance);
        if ($instance->d_send_to_email_14 && is_array($instance->d_send_to_email_14)) {
            $instance->d_send_to_email_14 = implode(',', $instance->d_send_to_email_14);
        }
    }
}