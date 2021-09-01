<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Void Request
 */
class VoidRequest extends AbstractRequest
{
    protected $action = 'VOID';

    public function getData()
    {
        $data = $this->getBaseData();

        return array_merge($data, $this->getBillingData());
    }
}
