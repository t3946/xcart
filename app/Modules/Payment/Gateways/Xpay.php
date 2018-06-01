<?php

namespace Modules\Payment\Gateways;


use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Xcart\App\Main\Xcart;

class Xpay extends Gateway
{
    public const PAYMENT_STATUS_NEW = 1;
    public const PAYMENT_STATUS_AUTHORIZED = 2;
    public const PAYMENT_STATUS_DECLINED = 3;
    public const PAYMENT_STATUS_CHARGED = 4;
    public const PAYMENT_STATUS_REFUNDED = 5;
    public const PAYMENT_STATUS_PARTIALY_REFUNDED = 6;

    public static function getProcessorName()
    {
        return 'Xpay';
    }

    public function init()
    {
        parent::init();

        $this->gateway->setShoppingCartId(GlobalConfigModel::objects()->get(['name' => 'xpc_shopping_cart_id'])->value);
        $this->gateway->setPublicKey(GlobalConfigModel::objects()->get(['name' => 'xpc_public_key'])->value);
        $this->gateway->setPrivateKey(GlobalConfigModel::objects()->get(['name' => 'xpc_private_key'])->value);
        $this->gateway->setPrivateKeyPassword(GlobalConfigModel::objects()->get(['name' => 'xpc_private_key_password'])->value);
        $this->gateway->setConfigurationId($this->model->param01);
        $this->gateway->setMerchantEmail(GlobalConfigModel::objects()->get(['name' => 'orders_department'])->value);

    }

    public function getLinks()
    {
        return [];
    }

    public function refund($params)
    {

    }

    public function void($params)
    {

    }

    public function lookup($params)
    {

    }

    public function authorize($params)
    {

    }

    public function capture($params)
    {

    }

    public function getState($mode)
    {

    }

    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params)
    {
        // TODO: Implement reauthorize() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function purchase($params)
    {
        $this->result = $this->gateway
            ->purchase($params)
            ->send();

        return $this->result->isSuccessful();
    }

    /**
     * @param $params
     * @return bool
     */
    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function success($params): void
    {
        if ($params['action'] === 'check_cart'){
            $this->check_cart($params);
            exit;
        }

        if ($this->txn = OrderTransactionModel::objects()->get(['transaction_id' => $params['txnId']])) {

            $response  = self::decrypt($params['updateData'] ?: '', $this->gateway->getPrivateKey(), $this->gateway->getPrivateKeyPassword());

            $response = str_replace(['<Response code>', '3dsecure'], ['<Response>', 'threedsecure'], $response); //Bug X-payments?

            if ($s_xml = simplexml_load_string($response)) {
                $updatedData = json_decode(json_encode((array) $s_xml), 1);

                switch ($updatedData['status']) {
                    case self::PAYMENT_STATUS_DECLINED :
                        $this->txn->transaction_status = OrderTransactionModel::STATUS_DECLINED;
                        break;
                    case self::PAYMENT_STATUS_AUTHORIZED :
                        $this->txn->transaction_status = OrderTransactionModel::STATUS_AUTHORIZED;

                        if ($this->get_detail_info(['transactionReference' => $params['txnId']])) {

                            $info = $this->result->getData();

                            if ($info && \is_array($info) && ($real_txns = $info['transactions']) && \is_array($real_txns)) {
                                foreach ($real_txns as $r_txn) {
                                    if (!empty($r_txn['txnid'])) {
                                        $this->txn->setAttributes([
                                            'transaction_response' => $r_txn,
                                            'transaction_id' => $r_txn['txnid'],
                                        ]);
                                    }
                                }
                            }
                        }

                        Xcart::app()->logger->debug('Response get_detail_info', $this->result->getData() ?? [], 'payment');

                        $this->txn->save();

                        break;
                }

                $this->txn->transaction_response = $updatedData;

                Xcart::app()->logger->debug('Response updated data', $updatedData, 'payment');
            } else {
                Xcart::app()->logger->error('Response XML not valid', $params, 'payment');
            }

        } else {
            Xcart::app()->logger->error('Transaction not found', $params, 'payment');
        }

        parent::success($params);
    }

    public function check_cart($params): void
    {
        echo $this->gateway
            ->check_card($params)
            ->getAnswer();
    }

    public function get_detail_info($params)
    {
        $this->result = $this->gateway
            ->get_detail_info($params)
            ->send();

        return $this->result->isSuccessful();
    }

    /**
     * Get string with prepended length
     * Length is 12 digits, leading zeroes added
     *
     * @param $str string
     *
     * @return string
     */
    public static function getStringLen($str)
    {
        return str_pad(strlen($str), 12, '0', STR_PAD_LEFT) . $str;
    }

    /**
     * Generate salt for request.
     * String of 32 characters
     *
     * IMPORTANT! Make sure the implementation of openssl_random_pseudo_bytes() in your PHP version
     * is indeed cryptographically secure. Please check:
     *  - https://bugs.php.net/bug.php?id=70014
     *
     * If necessary you may use another way to generate the cryptographically secure random string. See:
     *  - http://stackoverflow.com/questions/31492921/cryptographically-secure-random-string-function
     *
     * @return string
     */
    public static function getSalt()
    {
        return openssl_random_pseudo_bytes(32);
    }

    public static function encrypt(string $data, $key): string
    {
        // Initialize public key
        $publicKey = openssl_pkey_get_public($key);

        // 1) Get Salt block.
        //  - Generate 32-character string formed of random characters.
        $salt = self::getSalt();

        // 2) Get CRC block.
        //  - Generate MD5 in binary format of the passed data
        //  - Prepend it with the "     MD5" prefix (spaces are mandatory!)
        $crc = '     MD5' . md5($data, true);

        // 3) Data block.
        //  - For each Salt, CRC and Data calculate length: it's formatted as a 12-digit number, e.g. 000000000032.
        //  - Compose data block. Write consequently: length of Salt, Salt, length of CRC, CRC, length of Data, Data.
        $data = self::getStringLen($salt) . self::getStringLen($crc) . self::getStringLen($data);

        // 4) Split data by chunks of 128 characters
        $data = str_split($data, 128);

        // 5) Encrypt each chunk consequently using the public key
        foreach ($data as $key => $chunk) {
            $cryptText = null;
            openssl_public_encrypt($chunk, $cryptText, $publicKey);
            $data[$key] = $cryptText;
        }

        // 6) Encode each chunk with base64
        $data = array_map('base64_encode', $data);

        // 7) Compose the encrypted data.
        //  - Start with the "API" prefix
        //  - Write the encrypted and encoded chunks separated with line-break
        return 'API' . implode("\n", $data);
    }

    public static function decrypt(string $data, $pkey, $password): string
    {
        // Initialize the private key
        $res = openssl_pkey_get_private($pkey, $password);

        // Split and decode the encrypted chunks
        $data_a = array_map('base64_decode', explode("\n", substr($data, 3)));

        // Decrypt each chunk
        foreach ($data_a as $key => $str) {
            $decryptText = null;
            openssl_private_decrypt($str, $decryptText, $res);
            $data_a[$key] = $decryptText;
        }

        openssl_free_key($res);

        // Combine the decrypted chunks
        $result = implode('', $data_a);

        // Validate the CRC of the encrypted response
        return self::validateDecryptedData($result);
    }

    /**
     * Shift block from data string
     *
     * @param string &$data Response data
     *
     * @return string
     */
    public static function shiftBlock(&$data)
    {
        $length = intval(substr($data, 0, 12));

        $block = substr($data, 12, $length);

        $data = substr($data, 12 + $length);

        return $block;
    }

    /**
     * Check CRC of decrypted data
     *
     * @param string $data
     *
     * @return string
     * @throws \Exception
     */
    public static function validateDecryptedData($data)
    {
        // 1) Extract Salt
        //  - get the salt length from the first 12 characters
        //  - shift the salt block by it's length
        $salt = self::shiftBlock($data);

        // 2) Extract CRC
        //  - get the CRC length from the first 12 characters
        //  - shift the CRC block by it's length
        //  - remove the "     MD5" prefix from CRC
        $crc = substr(self::shiftBlock($data), 8);

        // 3) Extract data
        //  - get the data length from the first 12 characters
        //  - shift the data block by it's length
        $data = self::shiftBlock($data);

        // 4) Calculate the MD5 checksum in the binary format of the received data
        $dataCRC = md5($data, true);

        // 5) Compare it with CRC
        if ($dataCRC !== $crc) {
            throw new \Exception('Original CRC and calculated CRC is not equal');
        }

        return $data;
    }

}