<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


use Omnipay\Stripe\Message\PaymentIntents\Response;

class CancelPaymentIntentRequest extends \Omnipay\Stripe\Message\PaymentIntents\CancelPaymentIntentRequest
{
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new CancelPaymentIntentResponse($this, $data, $headers);
    }
}