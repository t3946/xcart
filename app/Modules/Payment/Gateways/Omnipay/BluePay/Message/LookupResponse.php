<?php

namespace Omnipay\BluePay\Message;

/**
 * BluePay Lookup Response
 */
class LookupResponse extends Response
{
    public function isSuccessful()
    {
        return isset($this->data) && isset($this->data['trans_type']);
    }

    public function getData()
    {
        $result = null;
        $data = parent::getData();
        if (isset($data['amount'])) {
            $result['amount'] = [
                'total' => $data['amount'],
                'currency' => 'USD'
            ];
        }
        if (isset($data['trans_type'])) {
            switch ($data['trans_type']) {
                case 'AUTH' :
                    $result['state'] = 'authorized';
                    $result['links'] = [
                        [
                            'rel' => 'capture',
                            'method' => 'POST'
                        ],
                        [
                            'rel' => 'void',
                            'method' => 'POST'
                        ],
                    ];
                    break;
                case 'CAPTURE' :
                    $result['state'] = 'completed';
                    $result['links'] = [
                        [
                            'rel' => 'refund',
                            'method' => 'POST'
                        ],
                    ];
                    break;
                case 'VOID' :
                    $result['state'] = 'voided';
                    unset($result['links']);
                    break;
            }
        }

        return array_merge($data, $result);
    }

    public function getTransactionReference()
    {
        return $this->valueFor('id');
    }
}
