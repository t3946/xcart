<?php

namespace Omnipay\PayPal\Message;

class RestLookupRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference');
        $this->validate('statusLookup');
        return array();
    }

    /**
     * Get HTTP Method.
     *
     * The HTTP method for fetchTransaction requests must be GET.
     * Using POST results in an error 500 from PayPal.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/'.$this->getParameter('statusLookup').'/' . $this->getTransactionReference();
    }

    public function setStatusLookup($value)
    {
        return $this->setParameter('statusLookup', $value);
    }

    public function getStatusLookup()
    {
        return $this->getParameter('statusLookup');
    }
}
