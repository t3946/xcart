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

    public function setConnectedAccount($value)
    {
        return $this->setParameter('connectedAccount', $value);
    }

    public function getConnectedAccount()
    {
        return $this->getParameter('connectedAccount');
    }

    public function setOffSession($value)
    {
        return $this->setParameter('offSession', $value);
    }

    public function getOffSession()
    {
        return $this->getParameter('offSession');
    }

    public function setConfirm($value)
    {
        return $this->setParameter('confirm', $value);
    }

    public function getConfirm()
    {
        return $this->getParameter('confirm');
    }

    public function setSetupFutureUsage($value)
    {
        return $this->setParameter('setupFutureUsage', $value);
    }

    public function getSetupFutureUsage()
    {
        return $this->getParameter('setupFutureUsage');
    }

    public function setCaptureMethod($value)
    {
        return $this->setParameter('captureMethod', $value);
    }

    public function getCaptureMethod()
    {
        return $this->getParameter('captureMethod');
    }


    public function getData()
    {
        $data = [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'payment_method_types' => ['card'],
            'capture_method' => 'manual',
            'description' => $this->getDescription(),
            'receipt_email' => $this->getOrder()->email,
            'metadata' => $this->getMetadata(),
            'off_session' => $this->getOffSession() ?? 'false',
            'confirm' => $this->getConfirm() ?? 'false',
            'customer' => $this->getCustomerReference(),
            'transfer_data' => $this->getConnectedAccount() ? ['destination' => $this->getConnectedAccount()] : []
        ];
        if ($this->getSetupFutureUsage()) {
            $data['setup_future_usage'] = $this->getSetupFutureUsage();
        }
        if ($this->getPaymentMethod()) {
            $data['payment_method'] = $this->getPaymentMethod();
        }
        if ($this->getCaptureMethod()) {
            $data['capture_method'] = $this->getCaptureMethod();
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new CreatePaymentIntentResponse($this, $data, $headers);
    }
}