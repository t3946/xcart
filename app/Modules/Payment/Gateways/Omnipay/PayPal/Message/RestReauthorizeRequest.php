<?php

namespace Omnipay\PayPal\Message;

class RestReauthorizeRequest extends AbstractRestRequest
{
    public function getData()
    {
        $this->validate('transactionReference');
        return array(
            'amount' => array(
                'currency' => $this->getCurrency(),
                'total' => $this->getAmount(),
            ),
        );
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/authorization/' . $this->getTransactionReference() . '/reauthorize';
    }
}
