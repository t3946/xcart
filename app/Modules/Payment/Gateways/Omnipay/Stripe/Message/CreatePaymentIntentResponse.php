<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


class CreatePaymentIntentResponse extends PaymentIntentResponse
{
    public function isSuccessful()
    {
        if (isset($this->data['object']) && 'payment_intent' === $this->data['object']) {
            return in_array($this->getStatus(), ['succeeded', 'requires_capture', 'requires_payment_method']);
        }

        return parent::isSuccessful();
    }
}