<?php

namespace Omnipay\Xpay\Message;


/**
 * Xpay Sale Request
 */
class SaleRequest extends AbstractRequest
{

    public function getData()
    {
        $data = $this->getBaseData();

        $data = array_merge($data, $this->getCartData());

        return $data;
    }

    public function getMethod()
    {
        return 'init';
    }

}
