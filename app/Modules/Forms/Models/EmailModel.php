<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Model;

/**
 * @property string|null from_address
 */
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
            'thread_id' => [
                'class' => CharField::class,
                'verboseName' => "ThreadId",
                'null' => true,
            ],
            'account_id' => [
                'class' => IntField::class,
                'verboseName' => "AccountId",
                'null' => false,
            ],
            'subject' => [
                'class' => CharField::class,
                'verboseName' => "Subject",
            ],
            'body' => [
                'class' => HasToOneField::class,
                'modelClass' => EmailBodyModel::class,
                'link' => ['id' => 'email_id'],
                'to' => 'id',
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
            'delivered_to_address' => [
                'class' => CharField::class,
                'verboseName' => "Delivered To",
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
            ],
            'date' => [
                'class' => DateTimeField::class,
                'null' => false
            ],
            'labels' => [
                'class' => ManyToManyField::class,
                'modelClass' => LabelModel::class,
                'through' => EmailLabelModel::class,
            ]
        ];
    }

    public function getFrom()
    {
        if (preg_match('/([^<]*)<?(.*)@(.*)>?/', $this->from_address, $m)) {
            $res = str_replace('"', '', $m[1] ?: $m[2] ?: $this->from_address);
            return "<span title='{$this->from_address}'>{$res}</span>";
        }
        return $this->from_address;
    }

    public function getSubject()
    {
        $res = $this->subject;
        if (trim($this->snippet)) {
            $res .= " - <span style='color:gray;'>{$this->snippet}</span>";
        }
        $color = '';
        $lbl = '';
        foreach ($this->labels->filter(['type' => 'user']) as $label) {
            $clr = "color: #666;";
            if ($label->color) {
                $clr = "color:{$label->color};";
            }
            $bclr = "background: #ddd";
            if ($label->background_color) {
                $bclr = "background:{$label->background_color}";
            }
            $color = "{$clr}{$bclr}";
            $lbl .= "<span style='font-size: 12px; border: 1px solid #DFDFDF; margin: 0 5px 0 0; border-radius: 4px; padding: 1px 5px; $color'>{$label}</span>";
        }
        return $lbl . $res;
    }
}