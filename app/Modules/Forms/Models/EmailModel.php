<?php


namespace Modules\Forms\Models;


use DateTime;
use DateTimeZone;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property string|null from_address
 */
class EmailModel extends Model
{

    public static function getFields()
    {
        $alias = EmailEntityModel::objects()->getTableAlias();
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
            'attachments' => [
                'class' => HasManyField::class,
                'modelClass' => EmailAttachmentModel::class,
                'link' => ['id' => 'email_id'],
                'to' => 'id',
                'verboseName' => "Attachments",
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
            ],
            'dx_models' => [
                'class' => ManyToManyField::class,
                'modelClass' => DistributorModel::class,
                'through' => EmailEntityModel::class,
                'extra' => ["{$alias}.model" => DistributorModel::class]
            ],
            'order_models' => [
                'class' => ManyToManyField::class,
                'modelClass' => OrderModel::class,
                'through' => EmailEntityModel::class,
                'extra' => ["{$alias}.model" => OrderModel::class]
            ],
            'viewed' => [
                'class' => ManyToManyField::class,
                'modelClass' => UserModel::class,
                'through' => EmailViewedModel::class
            ],
            'favorite' => [
                'class' => ManyToManyField::class,
                'modelClass' => UserModel::class,
                'through' => EmailFavoriteModel::class
            ],
            'action' => [
                'class' => ManyToManyField::class,
                'modelClass' => UserModel::class,
                'through' => EmailActionModel::class
            ],
            'children' => [
                'class' => HasManyField::class,
                'modelClass' => __CLASS__,
                'link' => ['thread_id' => 'thread_id'],
            ],
            'parent' => [
                'class' => ForeignField::class,
                'modelClass' => __CLASS__,
                'link' => ['thread_id' => 'message_id'],
            ]
        ];
    }

    public function isViewed(): bool
    {
       return $this->viewed->filter(['id' => Xcart::app()->user->id])->count() > 0;
    }

    public function isFavorite(): bool
    {
        return $this->favorite->filter(['id' => Xcart::app()->user->id])->count() > 0;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getAttachment()
    {
        return $this->attachments->asArray()->all();
    }

    public function getAction(int $id)
    {
        $filter = EmailActionModel::objects()->filter(['email_id' => $id]);
        if($filter->count() > 0){
            $name = $this->action->filter([
                'id' => $filter->asArray()->all()[0]['user_id']
            ])->asArray()->all()[0]['login'];

            return ['name' => $name, 'action' => true, 'date' =>  $filter->asArray()->all()[0]['date']];
        }
       return ['action' => false];
    }

    public function getFrom()
    {
        $from = $this->from_address;
        $res = $from;
        if (preg_match('/([^<]*)<?(.*)@(.*)>?/', $from, $m)) {
            $res = str_replace('"', '', $m[1] ?: $m[2] ?: $from);
            $res = "<span title='{$from}'>{$res}</span>";
        }
        if (!$this->isViewed()) {
            $res = "<b>{$res}</b>";
        }
        return $res;
    }

    public function getDate()
    {
        $oIssueDate = DateTime::createFromFormat('Y-m-d H:i:s', $res = $this->date, new DateTimeZone('EST'));
        $oIssueDate->setTime( 0, 0, 0 );
        $today = new DateTime(); // This object represents current date/time
        $today->setTime( 0, 0, 0 );
        $diff = $today->diff( $oIssueDate );
        $diffDays = (integer)$diff->format( "%R%a" );
        if ($diffDays === 0) {
            $res = $oIssueDate->format('H:m');
        } else {
            $res = $oIssueDate->format('M d');
        }

        if (!$this->isViewed()) {
            $res = "<b>{$res}</b>";
        }
        return $res;
    }

    public function getTo()
    {
        return $this->to_address ?: $this->delivered_to_address ;
    }

    public function getSubject()
    {
        $res = $this->subject;
        if (!$this->isViewed()) {
            $res = "<b>{$res}</b>";
        }
        if (trim($this->snippet)) {
            $res .= " - <span style='color:gray;'>{$this->snippet}</span>";
        }
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

    public function setViewed()
    {
        $this->getField('viewed')->setValue(Xcart::app()->user);
        $this->save();
    }
}