<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class EmailModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'message_id' => [
                'class' => CharField::class,
                'unique' => true,
                'verboseName' => "MessageId",
                'required' => true,
            ],
            'subject' => [
                'class' => CharField::class,
                'verboseName' => "Subject",
            ],
            'body' => [
                'class' => CharField::class,
                'verboseName' => "Body",
            ],
            'snippet' => [
                'class' => CharField::class,
                'verboseName' => "Snippet",
                'null' => true,
            ],
            'from_address' => [
                'class' => CharField::class,
                'verboseName' => "From",
                'null' => true,
            ],
            'to_address' => [
                'class' => CharField::class,
                'verboseName' => "To",
                'null' => true,
            ],
            'reply_to' => [
                'class' => CharField::class,
                'verboseName' => "Reply to",
                'null' => true,
            ],
            'type' => [
                'class' => CharField::class,
                'verboseName' => "Type",
                'choices' => [
                    'inbox' => 'Inbox',
                    'sent' => 'Sent',
                ],
                'null' => false,
                'default' => 'inbox'
            ]
        ];
    }
}