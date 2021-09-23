<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailBodyModel extends Model
{
    public static function getFields()
    {
        return [
            'email_body' => [
                'field' => 'body',
                'class' => FileField::class,
                'adapterName' => 'zip',
                'uploadTo' => 'files/email/body/%Y%m',
                'maxSize' => '35M',
                'null' => true,
                'default' => null,
            ],
            'email' => [
                'field' => 'email_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'link' => ['email_id' => 'id'],
                'primary' => true,
            ],
        ];
    }

    public function __toString()
    {
        $res = $this->email_body->get();
        return (string) ($res->read() ?: '');
    }
}