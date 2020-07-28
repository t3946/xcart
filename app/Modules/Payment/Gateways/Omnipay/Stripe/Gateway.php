<?php


namespace Omnipay\Stripe;

use Omnipay\Stripe\Message\CreatePaymentIntentRequest;

class Gateway extends PaymentIntentsGateway
{
    public function createPaymentIntent(array $parameters = [])
    {
        return $this->createRequest(CreatePaymentIntentRequest::class, $parameters);
    }
}