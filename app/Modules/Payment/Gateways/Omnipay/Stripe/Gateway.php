<?php


namespace Omnipay\Stripe;

use Modules\Payment\Gateways\Omnipay\Stripe\Message\CancelPaymentIntentRequest;
use Modules\Payment\Gateways\Omnipay\Stripe\Message\LookupPaymentIntentRequest;
use Modules\Payment\Gateways\Omnipay\Stripe\Message\RefundPaymentIntentRequest;
use Omnipay\Stripe\Message\AuthorizePaymentIntentRequest;
use Omnipay\Stripe\Message\CapturePaymentIntentRequest;
use Omnipay\Stripe\Message\CreatePaymentIntentRequest;

class Gateway extends PaymentIntentsGateway
{
    public function createPaymentIntent(array $parameters = [])
    {
        return $this->createRequest(CreatePaymentIntentRequest::class, $parameters);
    }

    public function capture(array $parameters = [])
    {
        return $this->createRequest(CapturePaymentIntentRequest::class, $parameters);
    }

    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizePaymentIntentRequest::class, $parameters);
    }

    public function cancel(array $parameters = [])
    {
        return $this->createRequest(CancelPaymentIntentRequest::class, $parameters);
    }

    public function fetchPaymentIntent(array $parameters = [])
    {
        return $this->createRequest(LookupPaymentIntentRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundPaymentIntentRequest::class, $parameters);
    }
}