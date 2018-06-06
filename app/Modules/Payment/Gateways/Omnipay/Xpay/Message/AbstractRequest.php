<?php

namespace Omnipay\Xpay\Message;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Gateways\Xpay;
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
        return $this->getOrder()->payment_method_model->cc_processor_models->limit(1)->get()->param01;
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
                'shippingCost' => number_format($order->shipping_cost,2,'.', ''),
                'taxCost' => number_format($order->tax,2,'.', ''),
                'discount' => number_format($order->discount + $order->coupon_discount, 2,'.', ''),
                'totalCost' => number_format($order->total, 2,'.', ''),
                'description' => "Order(s) # {$order->getOrderNumber()}",
                'merchantEmail' => $this->getMerchantEmail(),
                'shippingAddress' => [
                    'firstname' => $shipping['firstname'],
                    'address' => $shipping['address'][0],
                    'city' => $shipping['city'] ?? 'empty',
                    'state' => $shipping['state']->state ?? 'empty',
                    'zipcode' => $shipping['zipcode'] ?? 'empty',
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
            'request' => Xpay::encrypt($xml,$this->getPublicKey()),
        ];

        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $request)->send();
        $this->response = new Response($this, Xpay::decrypt($httpResponse->getBody(), $this->getPrivateKey(), $this->getPrivateKeyPassword()));
        return $this->response;
    }




}
