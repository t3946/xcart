<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


class RefundPaymentIntentRequest extends \Omnipay\Stripe\Message\RefundRequest
{
    public function getData()
    {
        return array_merge(parent::getData(), [
            'payment_intent' => $this->getTransactionReference(),
            'reverse_transfer' => 'true'
        ]);
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/refunds';
    }

    protected function createResponse($data, $headers = [])
    {
        return $this->response = new RefundPaymentIntentResponse($this, $data, $headers);
    }
}