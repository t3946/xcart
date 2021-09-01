<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;



class CapturePaymentIntentResponse extends PaymentIntentResponse
{
    public function getData()
    {
        $data = parent::getData();
        if ($data['amount_received'] > 0) {
            $data['amount'] = [
                'total' => $data['amount_received'] / 100,
                'currency' => strtoupper($data['currency'])
            ];
        }
        return $data;
    }
}