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
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * Class PbxAnveoCallModel
 *
 * @property (string) $login
 * @property (string) $session
 * @property \DateTime $start_at
 * @property \DateTime $end_at
 * @property (boolean) $is_lost
 * @property (boolean) $is_outgoing
 * @property (boolean) $processed
 * @property (string) $e164
 * @property (string) $rdnis
 * @property (string) $file
 * @property (string) $cname
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

            'processed' => [
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


}