<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;



class CancelPaymentIntentResponse extends PaymentIntentResponse
{
    public function isSuccessful()
    {
        return $this->isCancelled();
    }
}