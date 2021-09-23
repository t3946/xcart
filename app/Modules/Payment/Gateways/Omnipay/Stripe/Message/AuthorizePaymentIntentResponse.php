<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;



class AuthorizePaymentIntentResponse extends PaymentIntentResponse
{
    public function getData()
    {
        $data = parent::getData();
        if ($data['amount_capturable'] > 0) {
            $data['amount'] = [
                'total' => $data['amount_capturable'] / 100,
                'currency' => strtoupper($data['currency'])
            ];
        }
        return $data;
    }
}