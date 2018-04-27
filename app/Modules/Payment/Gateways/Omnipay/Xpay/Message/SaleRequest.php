<?php

namespace Omnipay\Xpay;

use Omnipay\Xpay\Message\AbstractRequest;

/**
 * Xpay Sale Request
 */
class SaleRequest extends AbstractRequest
{
    protected $action = 'SALE';

    public function getData()
    {
        return $this->getBaseData();
    }
}
