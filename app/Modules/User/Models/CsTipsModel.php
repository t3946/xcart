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
    public $capture_amount;
    public $hash;
    public $first_tip;
    public $second_tip;
    public $third_tip;
    public $tips = [];

    public function getHash()
    {
        /** @var OrderModel $order_model */
        if ($order_model = OrderModel::objects()->get(['orderid' => $this->order_id]) ){
            $login = $order_model->login;
            $date = $order_model->date;
            $this->hash = md5($login.$date);
        }
    }

    public function calculateOrderTips()
    {
        if ($order_model = OrderModel::objects()->get(['orderid' => $this->order_id]) ){

            $this->capture_amount = (new OrderStore($order_model))->getAdditionalCaptureAmount();
            $this->order_tips = min($this->capture_amount * 0.02, 50);

            if ($this->first_tip = min(15, (0.15 * $this->capture_amount - 0.01) ) ){
                $this->tips[] = round($this->first_tip,2 ,PHP_ROUND_HALF_UP);
            }
            if ($this->second_tip = 0.30 * min(100, $this->capture_amount) ){
                $this->tips[] = round($this->second_tip,2,PHP_ROUND_HALF_UP);
            }
            if ($this->third_tip = 0.50 * min(100, $this->capture_amount) ){
                $this->tips[] = round($this->third_tip,2,PHP_ROUND_HALF_UP);
            }

        }
    }

}