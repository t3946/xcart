<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Editor\Fields\EditorField;
use Modules\Order\Models\AttentionTagModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;

class DistributorRequestAvailForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'd_availability_must_be_checked',
            'd_send_to_email_14',
            'd_email_subject_14',
            'd_message_body_14',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        return [
            'd_availability_must_be_checked' => [
                'class' => CheckboxField::class,
                'label' => 'Availability must be checked before order is dispatched for fulfillment',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;', 'onchange' => "this.checked ? $('.click_hide').closest('tr').show() : $('.click_hide').closest('tr').hide()"],
            ],
            'd_send_to_email_14' => [
                'class' => CharField::class,
                'label' => '\'Send to\' email',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hidden' => $dx ? !$dx->d_availability_must_be_checked : false,
                'html' => ['class' => 'click_hide']
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
            'd_sec14_show_header' => [
                'class' => CheckboxField::class,
                'label' => 'Show header',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;'],
            ],
            'd_sec14_show_items_stock' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{items-stock}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;'],
            ],
            'd_sec14_show_shipto' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{shipto}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;'],
            ],
            'd_sec14_show_items_cost' => [
                'class' => CheckboxField::class,
                'label' => 'Show {{items-cost}}',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;'],
            ],
            'd_sec14_show_footer' => [
                'class' => CheckboxField::class,
                'label' => 'Show footer',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:50px;'],
            ],
            'd_server_min_distributor_time' => [
                'class' => CharField::class,
                'label' => 'Server time - Distributor time',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' =>'width:100px;'],
            ],
        ];
    }
}