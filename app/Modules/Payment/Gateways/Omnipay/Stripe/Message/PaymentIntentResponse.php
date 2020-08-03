<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


use Omnipay\Stripe\Message\PaymentIntents\Response;

abstract class PaymentIntentResponse extends Response
{
    public function getTransactionReference()
    {
        if (isset($this->data['object']) && 'payment_intent' === $this->data['object']) {
            if (!empty($this->data['id'])) {
                return $this->data['id'];
            }
        }

        return parent::getTransactionReference();
    }

    public function getData()
    {
        $data = parent::getData();
        if ($data['amount'] > 0) {
            $data['amount'] = [
                'total' => $data['amount'] / 100,
                'currency' => strtoupper($data['currency'])
            ];
        }
        $status = $this->getStatus();
        if ($data['object'] === 'payment_intent') {
            switch ($status) {
                case 'requires_capture' :
                    $data['links'] = [
                        [
                            'rel' => 'capture',
                            'method' => 'POST'
                        ],
                        [
                            'rel' => 'void',
                            'method' => 'POST'
                        ],
                    ];
                    break;
                case 'succeeded' :
                    $data['links'] = [[
                        'rel' => 'refund',
                        'method' => 'POST'
                    ]];
                    break;
            }
        }
        return $data;
    }
}