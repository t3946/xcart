<?php


namespace Omnipay\Stripe\Message;


use Modules\Payment\Gateways\Omnipay\Stripe\Message\AuthorizePaymentIntentResponse;

class AuthorizePaymentIntentRequest extends \Omnipay\Stripe\Message\PaymentIntents\AuthorizeRequest
{
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new AuthorizePaymentIntentResponse($this, $data, $headers);
    }
}