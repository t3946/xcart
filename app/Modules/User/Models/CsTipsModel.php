<?php


namespace Modules\User\Models;

use Modules\Order\Models\OrderModel;
use Modules\Order\Stores\OrderStore;
use Xcart\App\Orm\Model;

class CsTipsModel extends Model
{
    const PRIVATE_KEY = "y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=";
    const PUBLIC_KEY = "2r7bQsPMLds=";

    private $ad;
    public $order_id;
    public $encrypt;
    public $order_tips;

    public function EncryptOrderId()
    {
        $key = base64_decode(self::PRIVATE_KEY);
        $nonce = base64_decode(self::PUBLIC_KEY);
        $this->ad = '';

        $this->encrypt = sodium_crypto_aead_chacha20poly1305_encrypt($this->order_id, $this->ad, $nonce, $key);
    }

    public function calculateOrderTips()
    {
        if ($order_model = OrderModel::objects()->get(['orderid' => $this->order_id]) ){

            $capture_amount = (new OrderStore($order_model))->getAdditionalCaptureAmount();
            $this->order_tips = min($capture_amount * 0.02, 50);

        }

    }

    public function getOrderId(){
        $key = base64_decode(self::PRIVATE_KEY);
        $nonce = base64_decode(self::PUBLIC_KEY);
        $this->ad = '';
        $this->encrypt = base64_decode($_GET['e']);

        $this->order_id = sodium_crypto_aead_chacha20poly1305_decrypt($this->order_id, $this->ad, $nonce, $key);
    }
}