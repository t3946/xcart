<?php

namespace Omnipay\Coinbase\Message;

/**
 * Coinbase Purchase Response
 */
class PurchaseResponse extends FetchTransactionResponse
{
    public function getTransactionReference()
    {
        return $this->data['data']['code'] ?? null;
    }
}