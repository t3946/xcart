<?php


namespace Modules\Forms\Models;


use Modules\Order\Models\AttentionTagModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class TemplateModel extends Model
{
    public static function tableName()
    {
        return 'xcart_templates_for_communication';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'pos' => [
                'class' => IntField::class,
                'default' => 0
            ],
            'category' => [
                'field' => 'category_id',
                'class' => ForeignField::class,
                'modelClass' => TemplateCategoryModel::class,
                'link' => ['category_id' => 'id'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Category'
            ],
            'template_name' => [
                'class' => CharField::class,
                'verboseName' => 'Template name'
            ],
            'subject_line' => [
                'class' => CharField::class,
                'verboseName' => 'Subject line'
            ],
            'send_to_email' => [
                'class' => CharField::class,
                'verboseName' => "'Send to' email"
            ],
            'message_body' => [
                'class' => CharField::class,
                'verboseName' => "Message body"
            ],
            'department' => [
                'class' => CharField::class,
                'choices' => [
                    'customer' => 'Customer',
                    'distributor' => 'Distributor',
                    'our_customer_service' => 'Our customer service',
                    'third_party' => 'Third party',
                ],
                'verboseName' => 'Templates for communicating to'
            ],
            'ca_status' => [
                'class' => CharField::class,
                'default' => ''
            ],
            'status' => [
                'field' => 'status_id',
                'class' => ForeignField::class,
                'modelClass' => AttentionTagModel::class,
                'link' => ['status_id' => 'status_id'],
                'verboseName' => 'Add the following Attention tag'
            ],
            'attach_pdf_invoice' => [
                'class' => BooleanCharField::class,
                'default' => false,
                'verboseName' => 'Attach pdf invoice',
            ],
            'active' => [
                'class' => BooleanCharField::class,
                'default' => true
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->template_name;
    }
}