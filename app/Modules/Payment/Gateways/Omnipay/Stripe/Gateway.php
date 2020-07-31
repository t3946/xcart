<?php


namespace Omnipay\Stripe;

use Modules\Payment\Gateways\Omnipay\Stripe\Message\CancelPaymentIntentRequest;
use Modules\Payment\Gateways\Omnipay\Stripe\Message\LookupPaymentIntentRequest;
use Omnipay\Stripe\Message\CapturePaymentIntentRequest;
use Omnipay\Stripe\Message\CreatePaymentIntentRequest;

class Gateway extends PaymentIntentsGateway
{
    public function createPaymentIntent(array $parameters = [])
    {
        return $this->createRequest(CreatePaymentIntentRequest::class, $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest(CapturePaymentIntentRequest::class, $parameters);
    }

    public function cancel(array $parameters = array())
    {
        return $this->createRequest(CancelPaymentIntentRequest::class, $parameters);
    }

    public function fetchPaymentIntent(array $parameters = array())
    {
        return $this->createRequest(LookupPaymentIntentRequest::class, $parameters);
    }
}