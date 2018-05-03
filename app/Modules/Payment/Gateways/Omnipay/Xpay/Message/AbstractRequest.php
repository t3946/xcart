<?php

namespace Omnipay\Xpay\Message;
use Modules\Order\Models\OrderModel;
use Xcart\App\Helpers\Xml;

/**
 * Xpay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public const API_VERSION = '1.8';

    protected $liveEndpoint = 'https://secure.s3stores.com/xpayments/api.php';

    abstract public function getMethod();

    public function getShoppingCartId():? string
    {
        return $this->getParameter('shopping_cart_id');
    }

    public function setShoppingCartId($value): object
    {
        return $this->setParameter('shopping_cart_id', $value);
    }

    public function getPublicKey():? string
    {
        return $this->getParameter('public_key');
    }

    public function setPublicKey($value): object
    {
        return $this->setParameter('public_key', $value);
    }

    public function getPrivateKey():? string
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value): object
    {
        return $this->setParameter('private_key', $value);
    }

    public function getPrivateKeyPassword():? string
    {
        return $this->getParameter('private_key_password');
    }

    public function setPrivateKeyPassword($value): object
    {
        return $this->setParameter('private_key_password', $value);
    }

    public function getConfigurationId():? string
    {
        return $this->getOrder()->payment_method->cc_processor_models->limit(1)->get()->param01;
    }

    public function getMerchantEmail():? string
    {
        return $this->getParameter('merchant_email');
    }

    public function setMerchantEmail($value): object
    {
        return $this->setParameter('merchant_email', $value);
    }

    public function setOrder(object $value): object
    {
        return $this->setParameter('order', $value);
    }

    public function getOrder(): object
    {
        return $this->getParameter('order');
    }

    public function getEndPoint()
    {
        return $this->liveEndpoint;
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getHeaders()
    {
        return [
            "content-type" => 'application/x-www-form-urlencoded'
        ];
    }

    protected function getBaseData(): array
    {
        return [
            'api_version' => self::API_VERSION,
            'refId' => $this->getOrder()->orderid,
            'confId' => $this->getConfigurationId(),
            'returnUrl' => $this->getReturnUrl(),
            'callbackUrl' => $this->getNotifyUrl(),
            'target' => 'payment',
            'action' => $this->getMethod(),
            'language' => 'en',
        ];

    }

    protected function getCartData(): array
    {
        /** @var OrderModel $order */
        $order = $this->getOrder();

        [$shipping, $billing] = $order->getAddressInfo();

        foreach ($order->detail_models as $detail) {
            $items[] = ['items' => [
                    '@attributes' => ['type' => 'cell'],
                    'sku' => $detail->productcode,
                    'name' => $detail->product,
                    'price' => $detail->price,
                    'quantity' => $detail->amount,
                ]
            ];
        }

        $result = [
            'cart' => [
                'login' => $order->user_id ?: random_int(10,12),
                'currency' => $this->getCurrency(),
                'shippingCost' => number_format($order->shipping_cost,2),
                'taxCost' => number_format($order->tax,2),
                'discount' => number_format($order->discount + $order->coupon_discount, 2),
                'totalCost' => number_format($order->total, 2),
                'description' => "Order(s) # {$order->getOrderNumber()}",
                'merchantEmail' => $this->getMerchantEmail(),
                'shippingAddress' => [
                    'firstname' => $shipping['firstname'],
                    'address' => $shipping['address'][0],
                    'city' => $shipping['city'],
                    'state' => $shipping['state']->state,
                    'zipcode' => $shipping['zipcode'],
                    'country' => $shipping['country']->code,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'fax' => $order->fax,
                ],
                'billingAddress' => [
                    'firstname' => $billing['firstname'],
                    'address' => $billing['address'][0],
                    'city' => $billing['city'],
                    'state' => $billing['state']->state,
                    'zipcode' => $billing['zipcode'],
                    'country' => $billing['country']->code,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'fax' => $order->fax,
                ],
            ]
        ];

        $result['cart'] = array_merge($result['cart'], $items);

        return $result;
    }


    public function sendData($data)
    {
        preg_match("/<response>(.*)<\/response>/s", Xml::encode('response', $data, true), $matches);
        $xml = $matches[1];

        $request = [
            'cart_id' => $this->getShoppingCartId(),
            'request' => $this->encrypt($xml),
        ];

        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $request)->send();
        $this->response = new Response($this, $this->decrypt($httpResponse->getBody()));
        return $this->response;
    }

    /**
     * Get string with prepended length
     * Length is 12 digits, leading zeroes added
     *
     * @param $str string
     *
     * @return string
     */
    private function getStringLen($str)
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
    private function getSalt()
    {
        return openssl_random_pseudo_bytes(32);
    }

    public function encrypt(string $data): string
    {
        // Initialize public key
        $publicKey = openssl_pkey_get_public($this->getPublicKey());

        // 1) Get Salt block.
        //  - Generate 32-character string formed of random characters.
        $salt = $this->getSalt();

        // 2) Get CRC block.
        //  - Generate MD5 in binary format of the passed data
        //  - Prepend it with the "     MD5" prefix (spaces are mandatory!)
        $crc = '     MD5' . md5($data, true);

        // 3) Data block.
        //  - For each Salt, CRC and Data calculate length: it's formatted as a 12-digit number, e.g. 000000000032.
        //  - Compose data block. Write consequently: length of Salt, Salt, length of CRC, CRC, length of Data, Data.
        $data = $this->getStringLen($salt) . $this->getStringLen($crc) . $this->getStringLen($data);

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
        $result = 'API' . implode("\n", $data);

        return $result;
    }

    public function decrypt(string $data): string
    {
        // Initialize the private key
        $res = openssl_pkey_get_private($this->getPrivateKey(), $this->getPrivateKeyPassword());

        // Remove leading "API" word
        $data = substr($data, 3);

        // Split and decode the encrypted chunks
        $data = explode("\n", $data);
        $data = array_map('base64_decode', $data);

        // Decrypt each chunk
        foreach ($data as $key => $str) {
            $decryptText = null;
            openssl_private_decrypt($str, $decryptText, $res);
            $data[$key] = $decryptText;
        }

        openssl_free_key($res);

        // Combine the decrypted chunks
        $result = implode('', $data);

        // Validate the CRC of the encrypted response
        return $this->validateDecryptedData($result);
    }

    /**
     * Shift block from data string
     *
     * @param string &$data Response data
     *
     * @return string
     */
    private function shiftBlock(&$data)
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
     */
    private function validateDecryptedData($data)
    {
        // 1) Extract Salt
        //  - get the salt length from the first 12 characters
        //  - shift the salt block by it's length
        $salt = $this->shiftBlock($data);

        // 2) Extract CRC
        //  - get the CRC length from the first 12 characters
        //  - shift the CRC block by it's length
        //  - remove the "     MD5" prefix from CRC
        $crc = substr($this->shiftBlock($data), 8);

        // 3) Extract data
        //  - get the data length from the first 12 characters
        //  - shift the data block by it's length
        $data = $this->shiftBlock($data);

        // 4) Calculate the MD5 checksum in the binary format of the received data
        $dataCRC = md5($data, true);

        // 5) Compare it with CRC
        if ($dataCRC !== $crc) {
            throw new \Exception('Original CRC and calculated CRC is not equal');
        }

        return $data;
    }


}
