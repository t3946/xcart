<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


class RefundPaymentIntentResponse extends PaymentIntentResponse
{
    public function getTransactionReference()
    {
        if (isset($this->data['object']) && 'refund' === $this->data['object'] && !empty($this->data['payment_intent'])) {
            return $this->data['payment_intent'];
        }

        return parent::getTransactionReference();
    }

    public function isSuccessful()
    {
        if (isset($this->data['object']) && 'refund' === $this->data['object']) {
            return $this->getStatus() === 'succeeded';
        }

        return parent::isSuccessful();
    }

    public function getStatus()
    {
        if (isset($this->data['object']) && 'refund' === $this->data['object']) {
            return $this->data['status'];
        }

        return null;
    }
}