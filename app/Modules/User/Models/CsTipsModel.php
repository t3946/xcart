<?php


namespace Modules\User\Models;

use Xcart\App\Orm\Model;

class CsTipsModel extends Model
{
    const PRIVATE_KEY = "y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=";
    const PUBLIC_KEY = "2r7bQsPMLds=";

    private $ad;
    public $order_id;
    public $encrypt;

    public function getOrderId()
    {
        $key = base64_decode(self::PRIVATE_KEY);
        $nonce = base64_decode(self::PUBLIC_KEY);
        $this->ad = '';
        $this->encrypt = base64_decode($_GET['e']);

        $this->order_id = sodium_crypto_aead_chacha20poly1305_decrypt($this->encrypt, $this->ad, $nonce, $key);
    }
}