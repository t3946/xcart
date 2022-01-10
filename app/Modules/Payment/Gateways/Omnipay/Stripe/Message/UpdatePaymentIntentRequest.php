<?php


namespace Modules\Payment\Gateways\Omnipay\Stripe\Message;


use Omnipay\Stripe\Message\PaymentIntents\AbstractRequest;
use Omnipay\Stripe\Message\PaymentIntents\Response;

class UpdatePaymentIntentRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('paymentIntentReference');

        return [];
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/payment_intents/' . $this->getPaymentIntentReference();
    }

    protected function createResponse($data, $headers = [])
    {
        return $this->response = new Response($this, $data, $headers);
    }

    public function getShipping()
    {
        return $this->getParameter('shipping');
    }

    public function setShipping($value)
    {
        return $this->setParameter('shipping', $value);
    }

}