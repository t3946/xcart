<?php

namespace Modules\Order\Models;

use Modules\Order\Helpers\OrderHelper;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property PbxAnveoCallModel call
 */
class OrdersCallsModel extends Model
{
    use AutoMetaTrait;

    const TYPE_VIEWING_SAME_OPERATOR = 0;
    const TYPE_VIEWING_OTHER_OPERATOR = 1;
    const ORDER_PHONE_EQUALS_CALLED_PHONE = 2;

    public static function tableName()
    {
        return 'orders_calls';
    }

    public static function getFields()
    {
        return [

            'call' => [
                'field' => 'call_id',
                'class' => ForeignField::className(),
                'modelClass' => PbxAnveoCallModel::className(),
                'link' => ['call_id' => 'id'],
                'null' => false,
                'primary' => true
            ],

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['order_id' => 'orderid'],
                'null' => false,
            ],

            'relevance_type' => [
                'class' => IntField::className(),
                'length' => 1,
                'null' => false,
                'default' => 0,
                'choices' => [
                    0 => 'Order on call',
                    1 => 'Order on transferred call',
                    2 => 'Order phone'
                ],
            ],

            'relevance_order' => [
                'class' => IntField::className(),
                'null' => false
            ],

        ];
    }

    public function afterSave($owner, $isNew)
    {
        $log_category = "Calls_Record_Anveo";
        $log_text = serialize($this->getAttributes());
        func_backprocess_log($log_category, $log_text);

        if (($call = $this->call) && ($call->isVoiceMail() || $call->isLost())) {
            OrderHelper::setOrderTag($this->order_id, 62); //Voicemail left tag
        }
    }

}