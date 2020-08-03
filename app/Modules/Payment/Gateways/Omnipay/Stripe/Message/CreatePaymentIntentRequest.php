<?php


namespace Omnipay\Stripe\Message;



use Modules\Payment\Gateways\Omnipay\Stripe\Message\CreatePaymentIntentResponse;
use Omnipay\Stripe\Message\PaymentIntents\AbstractRequest;

class CreatePaymentIntentRequest extends AbstractRequest
{

    public function setOrder(object $value): object
    {
        return $this->setParameter('order', $value);
    }

    public function getOrder(): object
    {
        return $this->getParameter('order');
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/payment_intents';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getAmount()
    {
        return parent::getAmount() * 100;
    }

    public function getData()
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'payment_method_types' => ['card'],
            'capture_method' => 'manual',
            'description' => $this->getDescription(),
            'receipt_email' => $this->getOrder()->email,
            'metadata' => ['order' => $this->getOrder()->orderid],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new CreatePaymentIntentResponse($this, $data, $headers);
    }
}