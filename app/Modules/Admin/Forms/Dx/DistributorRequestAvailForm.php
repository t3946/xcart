<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorUtilityModel;
use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\TemplateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;

class DistributorRequestAvailForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

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
                'html' => ['style' => 'width:50px;', 'onchange' => "this.checked ? $('.click_hide').closest('tr').show() : $('.click_hide').closest('tr').hide()"],
            ],
            'request_avail_emails' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'choices' => function () use ($dx): array {
                    foreach ($dx->contacts_model->filter(['email__isnt' => '']) as $contact) {
                        $result[$contact->id] = $contact->getEmail();
                    }
                    return $result ?? [];
                },
                'selected' => $dx->contacts_model->filter(['utility__utility_id' => DistributorUtilityModel::REQUEST_AVAIL_UTILITY])->valuesList('id', true),
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide', 'style' => 'width:400px;'],
                'label' => 'Availability request contact'
            ],
            'request_avail_template' => [
                'class' => DropDownField::class,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'label' => 'Availability request template',
                'html' => ['class' => 'click_hide', 'style' => 'width:400px;'],
                'choices' => static function () {
                    foreach (TemplateModel::distributors() as $template) {
                        $result[$template->id] = (string)$template;
                    }
                    return $result ?? [];
                }
            ],
            'template_1' => [
                'class' => EditorField::class,
                'value' => $dx->request_avail_template->message_body,
                'label' => 'Availability request template body'
            ],
            'd_message_body_14' => [
                'class' => EditorField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide'],
                'label' => 'Old template body'
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

}