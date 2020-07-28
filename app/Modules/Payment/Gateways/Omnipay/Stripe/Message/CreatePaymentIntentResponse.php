<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


use Omnipay\Stripe\Message\PaymentIntents\Response;

class CreatePaymentIntentResponse extends Response
{
    public function getTransactionReference()
    {
        if (isset($this->data['object']) && 'payment_intent' === $this->data['object']) {
            if (!empty($this->data['id'])) {
                return $this->data['id'];
            }
        }

        return parent::getTransactionReference();
    }

    public function isSuccessful()
    {
        if (isset($this->data['object']) && 'payment_intent' === $this->data['object']) {
            return in_array($this->getStatus(), ['succeeded', 'requires_capture', 'requires_payment_method']);
        }

        return parent::isSuccessful();
    }

    public function getData()
    {
        return array_replace(parent::getData(), ['amount' => ['total' => $this->data['amount'] / 100, 'currency' => strtoupper($this->data['currency'])]]);
    }
}