<?php

namespace Modules\PBX\Models;

use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrdersCallsModel;
use Modules\User\Models\PbxOptionsModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
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
 * @property \DateTime $start_at
 * @property \DateTime $end_at
 * @property (boolean) $is_lost
 * @property (boolean) $is_outgoing
 * @property (boolean) $is_voice_mail
 * @property (string) $e164
 * @property (string) $rdnis
 * @property (string) $file
 * @property (string) $cname
 *
 * @property OrderModel[]| $orders
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

            'id' => AutoField::className(),

            'account' => [
                'field' => 'anveo_account',
                'class' => ForeignField::className(),
                'modelClass' => PbxOptionsModel::className(),
                'link' => [ 'anveo_account' => 'anveo_account'],
            ],

            'orders' => [
                'class' => ManyToManyField::className(),
                'modelClass' => OrderModel::className(),
                'through' => OrdersCallsModel::className(),
            ],

            'options' => [
                'class' => HasToOneField::className(),
                'modelClass' => PbxOptionsModel::className(),
                'link' => ['anveo_account' => 'anveo_account'],
            ],

            'session' => [
                'class' => CharField::className(),
                'null' => false,
            ],

            'file' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'cname' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'e164' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'rdnis' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'start_at' => [
                'class' => DateTimeField::className(),
                'null' => false
            ],

            'end_at' => [
                'class' => DateTimeField::className(),
                'null' => true,
                'default' => null,
            ],

            'is_lost' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false
            ],

            'is_outgoing' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false
            ],

            'is_voice_mail' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false
            ],

        ];
    }

    public function isIncoming(){
        return !$this->isOutgoing();
    }

    public function isOutgoing(){
        return $this->is_outgoing;
    }

    public function isLost(){
        return $this->is_lost;
    }

    public function isVoiceMail(){
        return $this->is_voice_mail;
    }


}