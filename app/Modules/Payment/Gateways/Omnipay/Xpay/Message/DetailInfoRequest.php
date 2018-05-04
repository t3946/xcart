<?php

namespace Omnipay\Xpay\Message;


/**
 * Xpay DetailInfo Request
 */
class DetailInfoRequest extends AbstractRequest
{

    public function getData()
    {
        $data = [
            'api_version' => self::API_VERSION,
            'target' => 'payment',
            'txnId' => $this->getTransactionReference(),
        ];

        return $data;
    }

    public function getMethod()
    {
        return 'get_additional_info';
    }

}
