<?php


namespace Omnipay\Stripe\Message;


use Modules\Payment\Gateways\Omnipay\Stripe\Message\CapturePaymentIntentResponse;

class CapturePaymentIntentRequest extends \Omnipay\Stripe\Message\PaymentIntents\CaptureRequest
{
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new CapturePaymentIntentResponse($this, $data, $headers);
    }
}