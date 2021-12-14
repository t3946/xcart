<?php

namespace Modules\Order\Models;


use Modules\Forms\Helpers\SnippetHelper;
use Modules\Translate\Models\LanguageModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\RenderTrait;

/**
 * @property string code
 * @property string customer_subject
 * @property string copy_subject,
 * @property string email_body
 * @property string enabled
 * @property string customer_attach_pdf_invoice
 * @property string admin_attach_pdf_invoice
 * @property int lang_id
 * @property LanguageModel|null lang
 */
class OrderStatusNotificationModel extends Model
{
    use AutoMetaTrait;
    use RenderTrait;

    public static function tableName()
    {
        return 'xcart_order_status_notifications';
    }

    public static function getFields()
    {
        return [
            'notification_id' => AutoField::class,
            'code' => [
                'class' => CharField::class,
                'verboseName' => 'Code'
            ],
            'customer_subject' => [
                'class' => CharField::class,
                'verboseName' => 'Subject line (email to customer)'
            ],
            'copy_subject' => [
                'class' => CharField::class,
                'verboseName' => 'Subject line (email-copy to us)',
                'default' => '',
            ],
            'email_body' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Body'
            ],
            'enabled' => [
                'class' => BooleanCharField::class,
                'default' => 'Y',
            ],
            'customer_attach_pdf_invoice' => [
                'class' => BooleanCharField::class,
                'verboseName' => 'Attach PDF invoice',
                'default' => 'Y',
            ],
            'admin_attach_pdf_invoice' => [
                'class' => BooleanCharField::class,
                'verboseName' => 'Attach PDF invoice',
                'default' => 'Y',
            ],
            'lang' => [
                'field' => 'lang_id',
                'class' => ForeignField::class,
                'modelClass' => LanguageModel::class,
                'null' => true,
                'default' => null,
                'link' => ['lang_id' => 'lang_id'],
                'verboseName' => 'Lang',
                'choices' => function () {
                    $ar_lang = LanguageModel::objects()->all();
                    foreach ($ar_lang as $lang_model) {
                        $options[$lang_model->pk] = (string)$lang_model;
                    }
                    return $options ?? [];
                },
            ],
        ];
    }

    public function render($name, $params)
    {
        return SnippetHelper::render($this->{$name}, $params);
    }
}