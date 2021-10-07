<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailAttachmentModel extends Model
{
    public static function getFields()
    {
        return [
            'attachment_content' => [
                'field' => 'attachment',
                'class' => FileField::class,
                'adapterName' => 's3',
                'uploadTo' => 'emails/attachments/%Y%m',
                'maxSize' => '100M',
                'null' => true,
                'default' => null,
            ],
            'filename' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'email' => [
                'field' => 'email_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'link' => ['email_id' => 'id'],
                'primary' => true,
            ],
            'cid' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ];
    }
}