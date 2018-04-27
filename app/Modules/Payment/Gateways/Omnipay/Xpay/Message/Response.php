<?php

namespace Omnipay\Xpay\Message;


use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return !array_key_exists('errorCode', $this->data);
    }
}