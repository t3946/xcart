<?php


namespace Modules\User\Models;

use Modules\Order\Models\OrderModel;
use Modules\Order\Stores\OrderStore;
use Xcart\App\Orm\Model;

class CsTipsModel extends Model
{
    public $order_id;
    public $encrypt;
    public $order_tips;

    public function EncryptOrderId()
    {
        $this->encrypt =  base64_encode($this->order_id);
    }

    public function calculateOrderTips()
    {
        if ($order_model = OrderModel::objects()->get(['orderid' => $this->order_id]) ){

            $capture_amount = (new OrderStore($order_model))->getAdditionalCaptureAmount();
            $this->order_tips = min($capture_amount * 0.02, 50);
        }

    }

    public function decryptOrderId(){
        $this->order_id = base64_decode($this->encrypt);
    }
}