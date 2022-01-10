<?php

namespace Modules\Mail\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Storage\FileNameHasher\MD5FileContentHasher;

/**
 * @property int unique_id
 * @property float weight
 * @property string date
 * @property ImageField image_path
 * @property FileField $file
 * @property string type
 * @property string status
 * @property string source
 *
 */
class MailboxForwardingModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_mailboxforwarding';
    }

    public static function getFields()
    {
        return [
            'mail_id' => AutoField::class,
            'image_path' => [
                'verboseName' => 'Image',
                'class' => ImageField::class,
                'adapterName' => 's3',
                'uploadTo' => "mailbox/image/%Y%m",
                'nameHasher' => MD5FileContentHasher::class,
                'null' => true,
                'default' => null
            ],
            'status' => [
                'class' => CharField::class,
                'null' => true,
                'default' => '',
            ],
            'type' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    'Letter' => 'Letter',
                    'Package' => 'Package'
                ]
            ],
            'file' => [
                'verboseName' => 'File',
                'class' => FileField::class,
                'adapterName' => 's3',
                'uploadTo' => "mailbox/file/%Y%m",
                'nameHasher' => MD5FileContentHasher::class,
                'null' => true,
                'default' => null
            ],
            'date' => [
                'class' => DateField::class,
            ],
            'unique_id' => [
                'class' => IntField::class,
                'null' => true,
                'default' => ''
            ],
            'weight' => [
                'class' => FloatField::class,
                'null' => true,
                'default' => '',
            ],
            'source' => [
                'class' => CharField::class,
                'null' => true,
                'default' => 'auto',
            ]
        ];
    }

    public function getImagePath(): string
    {
        return "https://i1.s3stores.com/{$this->image_path->getValue()}";
    }
    public function getFilePath(): string
    {
        return "https://i1.s3stores.com/{$this->file->getValue()}";
    }
}