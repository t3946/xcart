<?php


namespace Modules\Forms\Models;


use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\Decisions\DecisionTypeModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TreeForeignField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * @property string message_body
 * @property string subject_line
 * @property AttentionTagModel status
 * @method static Manager distributors()
 * @method static Manager customer_service()
 */
class TemplateModel extends Model
{
    public const REQUEST_AVAILABILITY_TEMPLATE_ID = 9614;
    public const ORDER_ENTRY_TEMPLATE_ID = 8974;
    public const DISPATCH_ORDER_TEMPLATE_ID = 9621;

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
                'class' => TreeForeignField::class,
                'modelClass' => TemplateCategoryModel::class,
                'link' => ['category_id' => 'id'],
                'null' => false,
                'verboseName' => 'Template category'
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
            'decision_type' => [
                'field' => 'decision_type_id',
                'class' => ForeignField::class,
                'modelClass' => DecisionTypeModel::class,
                'link' => ['decision_type_id' => 'decision_type_id']
            ]
        ];
    }

    public static function distributorsManager($instance = null)
    {
        if ($dx_category = TemplateCategoryModel::objects()->get(['id' => 28])) {
            return static::objects($instance)->filter([
                'category__lft__gte' => $dx_category->lft,
                "category__rgt__lte" => $dx_category->rgt,
                "category__root" => $dx_category->root])
                ->order(['category__root', 'category__level', 'category__pos']);
        }
    }

    public static function customer_serviceManager($instance = null)
    {
        if ($dx_category = TemplateCategoryModel::objects()->get(['id' => 29])) {
            return static::objects($instance)->filter([
                'category__lft__gte' => $dx_category->lft,
                "category__rgt__lte" => $dx_category->rgt,
                "category__root" => $dx_category->root])
                ->order(['category__root', 'category__level', 'category__pos']);
        }
    }

    public function __toString()
    {
        return (string)($this->template_name ?? 'Template');
    }
}