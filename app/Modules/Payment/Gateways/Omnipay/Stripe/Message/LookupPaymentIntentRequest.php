<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


use Omnipay\Stripe\Message\PaymentIntents\FetchPaymentIntentRequest;

class LookupPaymentIntentRequest extends  FetchPaymentIntentRequest
{
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new LookupPaymentIntentResponse($this, $data, $headers);
    }
}