<?php

namespace Modules\PBX\Models;

use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Exception;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrdersCallsModel;
use Modules\PBX\Helpers\AnveoAssignCalls;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * Class PbxAnveoCallModel
 *
 * @property (int) $id
 * @property (string) $login
 * @property (string) $session
 * @property (string) $anveo_account
 * @property DateTime $start_at
 * @property DateTime $end_at
 * @property (boolean) $is_lost
 * @property (boolean) $is_outgoing
 * @property (boolean) $is_voice_mail
 * @property (string) $e164
 * @property (string) $rdnis
 * @property (string) $file
 * @property (string) $cname
 *
 * @property OrderModel[] $orders
 * @property PbxOptionsModel|null $options
 *
 * @package Modules\Anveo\Models
 */
class PbxAnveoCallModel extends Model
{

    public static function tableName()
    {
        return 'anveo_calls';
    }

    public static function getFields()
    {
        return [

            'id' => AutoField::class,
            'account' => [
                'class' => ForeignField::class,
                'field' => 'anveo_account',
                'sqlType' => Types::STRING,
                'modelClass' => PbxOptionsModel::class,
                'link' => [ 'anveo_account' => 'anveo_account'],
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
                'verboseName' => 'Operator'
            ],
            'orders' => [
                'class' => ManyToManyField::class,
                'modelClass' => OrderModel::class,
                'through' => OrdersCallsModel::class,
            ],
            'bind_calls' => [
                'class' => HasManyField::class,
                'modelClass' => OrdersCallsModel::class,
                'link' => ['id' => 'call_id'],
            ],
            'options' => [
                'class' => HasToOneField::class,
                'modelClass' => PbxOptionsModel::class,
                'link' => ['anveo_account' => 'anveo_account'],
            ],
            'session' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'file' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'cname' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Party Details'
            ],
            'e164' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Party Tel #'
            ],
            'rdnis' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'start_at' => [
                'class' => DateTimeField::class,
                'null' => false,
                'verboseName' => 'Start Time'
            ],
            'end_at' => [
                'class' => DateTimeField::class,
                'null' => true,
                'default' => null,
            ],
            'is_lost' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],
            'is_outgoing' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],
            'is_voice_mail' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],
            'listens' => [
                'class' => HasManyField::class,
                'modelClass' => AnveoListensModel::class,
                'link' => ['id' => 'call_id']
            ]
        ];
    }

    public function isIncoming(): bool
    {
        return !$this->isOutgoing();
    }

    public function isOutgoing(): bool
    {
        return $this->is_outgoing;
    }

    public function isLost(): bool
    {
        return $this->is_lost;
    }

    public function isVoiceMail(): bool
    {
        return $this->is_voice_mail;
    }

    public function getUrl(): string
    {
        if (!empty($this->file)) {
            if ($this->isOutgoing()) {
                $account = AnveoAssignCalls::parseAccount($this->file);
                return "https://s3.amazonaws.com/anveo-{$account}/{$this->file}";
            }

            return "https://s3.amazonaws.com/incoming_business_hours/{$this->file}";
        }

        return '';
    }

    public function getFrontendE164(): string
    {
        $e164 = "Not defined";
        if ($this->e164){
            $e164 = "+" . $this->e164;
            if (strlen($e164) > 10){
                $first_section = $e164[1];
                $second_section = substr($e164, 2, 3);
                $third_section = substr($e164, 5, 3);
                $forth_section = substr($e164, 8);
                $e164 = "+{$first_section} ({$second_section}) {$third_section}-{$forth_section}";
            }

        }
        return $e164;
    }

    public function getDirection(): string
    {
        if ($this->isOutgoing()) {
            $direction = 'Outbound';
        }
        elseif ($this->isLost()) {
            $direction = 'Missed call';
        }
        elseif ($this->isVoiceMail()) {
            $direction = 'Voicemail';
        }
        else {
            $direction = 'Inbound';
        }
        return $direction;
    }

    /**
     * @return DateInterval|false
     * @throws Exception
     */
    public function getDuration()
    {
        return (new DateTime($this->end_at))->diff(new DateTime($this->start_at));
    }

}